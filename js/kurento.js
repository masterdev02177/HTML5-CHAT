var Kurento = function(callBackReady) {
	this.callBackReady = callBackReady;
	this.myStreamName = '';
	/**
	 * The latter part is party taken from kurento one2-many tutorial code.
	 */
	var socket;

	var webRtcPeers={}; //Different peers for different streams

	var streamToPlay='';

	/**
	 *
	 * @param {string} streamName A string with a stream name
	 * @param {HTMLVideoElement} dom Video element to bind stream to
	 */
	this.playStream = function(streamName, dom){
		playKurentoStream(streamName, dom);
	}

	/**
	 * This stops playing the stream name
	 * @param {string} streamName A string with a stream name. Have to contain '_publisher' or '_viewer'
	 */
	this.stopStream = function(streamName){
		stopKurentoStream(streamName);
	}

	/**
	 * Publishes a video stream with a name provided
	 * The method already knows the stream name from myStreamName var, which comes from socket.io event.
		 * You can modify that behavior. Please refer to socket.on('stream:streamName') event
	 * @param {HTMLVideoElement} dom Element to attach video to
	 */
	this.publishStream = function(dom, record){
		//the second param tells that you need to record stream
		publishKurentoStream(dom, record);
	}


//---------------------------------------------------------
// Utilities




//Once DOM is loaded - attach events to buttons
	window.onload = function() {
		socket = io('https://webtv.fr:8080', {secure: true});
		socket.on('connected', function (){
			console.log('Connected to socket.io');
		});
		socket.on('stream:publish:response', publishResponse);
		socket.on('stream:play:response', playResponse);
		socket.on('stream:stop', function (msg){
			dispose(msg.streamName);
		});
		socket.on('stream:iceCandidate', function (msg){
			webRtcPeers[msg.streamName+'_'+msg.type].addIceCandidate(msg.candidate);
		});

		socket.on('stream:streamName', function (msg){
			console.log('my stream name is: ',msg.name)
			serverWebrtc.myStreamName = msg.name;
			if (typeof serverWebrtc.callBackReady=='function') {
				serverWebrtc.callBackReady();
			}

		});
	}

	window.onbeforeunload = function() {
		// ws.close();
		//todo close connection to socket.io
	}

	/**
	 * Called when publish request response arrieves
	 * @param {*} message
	 */
	function publishResponse(message) {
		if (message.response != 'accepted') {
			var errorMsg = message.message ? message.message : 'Unknown error';
			console.warn('Call not accepted for the following reason: ' + errorMsg);
			dispose(message.streamName);
		} else {
			webRtcPeers[message.streamName+'_publisher'].processAnswer(message.sdpAnswer);
		}
	}

	/**
	 * Called once play response arrives
	 * @param {*} message
	 */
	function playResponse(message) {
		if (message.response != 'accepted') {
			var errorMsg = message.message ? message.message : 'Unknow error';
			console.warn('Call not accepted for the following reason: ' + errorMsg);
			dispose(message.streamName);
		} else {
			webRtcPeers[message.streamName+'_viewer'].processAnswer(message.sdpAnswer);
		}
	}

	/**
	 * Creates a publish connection to Kurento
	 * @param {HTMLVideoElement} dom
	 * @param {boolean} record
	 */
	function publishKurentoStream(dom, record) {
		record = record || false; //false by default
		if (!webRtcPeers[serverWebrtc.myStreamName+'_publisher']) {
			var options = {
				localVideo: dom,
				mediaConstraints: {
					audio: true,
					video: {
						width: 320,
						framerate: 12
					}
				},
				onicecandidate : function (candidate){
					onIceCandidate(candidate,'publisher');
				}
			}

			webRtcPeers[serverWebrtc.myStreamName+'_publisher'] = kurentoUtils.WebRtcPeer.WebRtcPeerSendonly(options, function(error) {
				if(error) return onError(error);

				this.generateOffer(function(error, offerSdp){
					onOfferPresenter(error, offerSdp, record);
				});
			});
		}
	}

	/**
	 * Call offer sdp callback
	 * @param {*} error
	 * @param {*} offerSdp
	 * @param {bool} record
	 */
	function onOfferPresenter(error, offerSdp, record) {
		if (error) return onError(error);

		sendEvent('stream:publish',{
			sdpOffer : offerSdp,
			record : record
		});
	}

	/**
	 * Creates a play connection to Kurento
	 * @param {*} streamName
	 * @param {*} dom
	 */
	function playKurentoStream(streamName, dom) {
		if (!webRtcPeers[streamName+'_viewer']) {
			var options = {
				remoteVideo: dom,
				onicecandidate : function (candidate){
					onIceCandidate(candidate, 'viewer');
				}
			}

			webRtcPeers[streamName+'_viewer'] = kurentoUtils.WebRtcPeer.WebRtcPeerRecvonly(options, function(error) {
				if(error) return onError(error);

				this.generateOffer(function (error, offerSdp){
					if (error) return onError(error)

					sendEvent('stream:play',{
						publisherName: streamName, //todo place the correct value here
						sdpOffer : offerSdp
					});
				});
			});
		}
	}

	/**
	 * Called on ICE candidate arrives and sends it to server
	 * @param {*} candidate
	 * @param {publisher|viewer} type of stream
	 */
	function onIceCandidate(candidate,type) {
		console.log('Local candidate' + JSON.stringify(candidate));

		sendEvent('onIceCandidate',{
			candidate : candidate,
			type: type
		});
	}

	/**
	 * Stop playing stream
	 * @param {string} streamName
	 */
	function stopKurentoStream(streamName) {
		var splitted=streamName.split('_');
		if (splitted.length<2){
			console.warn('Not a correct stream name. Need to have type after _')
			return;
		}

		if (webRtcPeers[streamName]) {
			if (splitted[1]=='publisher'){
				//notify all if you stop a publisher
				sendEvent('stream:stop',{});
			}
			dispose(streamName);
		}
	}

	/**webRtcPeers
	 * Disppose webrtc connection
	 */
	function dispose(streamName) {
		if (webRtcPeers[streamName]) {
			webRtcPeers[streamName].dispose();
			delete webRtcPeers[streamName];
		}
	}

	/**
	 * A wrapper for socket.emit
	 * @param {string} event
	 * @param {object|array|null} data
	 */
	function sendEvent(event, data) {
		console.log('Senging event: ' + event, data);
		socket.emit(event, data);
	}



}




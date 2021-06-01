'use strict';

// Update our socketMediaSoup.io status.

// These are the main parameters.
var SOCKETIO_SERVER = chatHTML5.config.webrtcServerUrl.trim();//'email5.webtv.fr:8443';
console.log('Starting...');

// We can save a lot of bandwidth by constraining the dimensions.
// Note that this doesn't work so well on some iOS, so we handle
// that by reissuing a getUserMedia without the dimensions below.
var VIDEO_CONSTRAINTS = {facingMode: 'user', width: 320, height: 240};

// In case you need them.  See the MediaSoup client API.
var TRANSPORT_OPTIONS = {};

// We start off without publishing enabled.
var publishing = false;

// The session counter, so we can send multiple requests to the server.
var lastSession = 0;
var webrtcPublished;

// A shorthand for the MediaSoup client.
var ms = window.mediasoupClient;
if (!ms.isDeviceSupported()) {
    // Too bad.
    // Maybe try upgrading your mediasoup-client.js (but make sure it is still v2!)
    alert('Sorry, Mediasoup is not supported on this device');
}

var remoteFeeds = new Map();


// Connect to the socketMediaSoup.io pubsub server (../server.js), and update status.
var socketMediaSoup = io.connect(SOCKETIO_SERVER);
socketMediaSoup.on('connect', function conn() {
    console.log('mediaSoupConnected');
});
socketMediaSoup.on('disconnect', function disco(reason) {
    console.log('mediaSoupDiscConnected');
});

// Helper function to send objects via socketMediaSoup.io.
function sendMS(obj) {
    console.log('send:', obj);

    // We always use the 'ms' message type to send to the pubsub server.
    socketMediaSoup.emit('ms', obj);
}

// The getUserMedia() stream of the publisher.
var gumStream;

// Mapping of a session (multiple sessions for one socketMediaSoup.io socketMediaSoup) to
// a MediaSoup client Rooms.
var roomsMS = {};

// A list of all the producers we create for the publisher.
var producers = [];

// The session corresponding to the current publisher, if any.
var pubsession;

// Whether the publisher is muted.
var pubmuted = false;

// The current request id (monotonically increasing), to map messages
// to their callbacks.
var reqid = 0;

// Map of request id to success callback (which takes a parameter indicating
// the response.
var pending = {};

// Map of request id to error callback (which takes a parameter indicating
// the error type).
var errors = {};

// Handle the 'ms' socketMediaSoup.io messages.
socketMediaSoup.on('ms', function recv(obj) {
    console.log('recv', obj);
    switch (obj.type) {
        case 'MS_RESPONSE':
        {
            // It was a response from the mediasoup server.
            // Look up our success callback.
            var cb = pending[obj.meta.id];

            // Release the callback/errback maps for this request id.
            delete pending[obj.meta.id];
            delete errors[obj.meta.id];
            if (cb) {
                // If there was a callback, call it with MediaSoup's payload.
                cb(obj.payload);
            }
            break
        }

        case 'MS_ERROR':
        {
            // It was an error from the mediasoup server.
            // Look up our error callback.
            var errb = errors[obj.meta.id];

            // Release the callback/errback maps for this request id.
            delete pending[obj.meta.id];
            delete errors[obj.meta.id];
            if (errb) {
                // If there was an errback, call it with MediaSoup's error.
                errb(obj.payload);
            }
            break;
        }

        case 'MS_NOTIFY':
        {
            // It was a notification.
            // The only target for client notifications is the Room
            // corresponding to this session.
            var room = roomsMS[obj.meta.session];
            if (room) {
                // Receive the notification with MediaSoup's payload.
                room.receiveNotification(obj.payload);
            }
            break;
        }

        // FIXME: Maybe do something to log unknown protocol.
    }
});

function stopStream(streamid) {
    // We can just delete the Room, since MediaSoup client will clean up after the MS_STOP.
    var session = remoteFeeds.get(streamid);
    if (session) {
        delete roomsMS[session];
        // Don't expect an error or response.
        sendMS({type: 'MS_STOP', payload: {}, meta: {id: ++reqid, session: session}});
    }
}


// Do the actions needed for the isMuted setting.
function mute(isMuted) {
    console.log('Applying mute', isMuted);
    // Loop over all the publisher's producers.
    for (var producer of producers) {
        if (producer.kind !== 'audio') {
            // Only pause/resume the audio producers.
            continue;
        }
        if (isMuted) {
            // Mute by pausing the audio producer.
            producer.pause();
        } else {
            // Unmute by resuming the audio producer.
            producer.resume();
        }
    }
}

// This is a timeout needed on some platforms to
// properly do an action only when a stream has become
// active.
var streamActiveTimeout = {};

// Do an action when stream is active.
function whenStreamIsActive(getStream, callback) {
    // Call the getter function to obtain the target stream.
    var stream = getStream();
    if (!stream) {
        // If the stream is no longer present, just return.
        return;
    }

    // Get the stream's id.
    var id = stream.id;
    if (stream.active) {
        // It's active, so call the callback.
        callback();
    }
    else if ('onactive' in stream) {
        // We can set an active handler.
        stream.onactive = maybeCallback;
    }
    else if (!streamActiveTimeout[id]) {
        // Some platforms, like Safari, don't support
        // onactive, so we need to keep retrying after timeout.
        // We aren't waiting for this stream yet,
        // so try setting a timeout.
        maybeCallback();
    }

    function maybeCallback() {
        delete streamActiveTimeout[id];
        var stream = getStream();
        if (!stream || stream.id !== id) {
            // Not present anymore, or ID doesn't match
            // the one we were called with.
            return;
        }
        if (stream.onactive === maybeCallback) {
            // Remove the onactive handler.
            stream.onactive = null;
        }
        if (!stream.active) {
            // Safari needs a timeout to try again.
            // console.log('try again');
            streamActiveTimeout[id] = setTimeout(maybeCallback, 500);
            return;
        }

        // The stream is active, so callback.
        callback();
    }
}

// Set a stream as the video tag's source.
function setVideoSource(video, stream) {
    if (!stream) {
        // No stream, so remove it from the video tag...
        if (video) {
            // ... if there is one.

            try {
                video.removeAttribute('src');
                // srcObject is not always available.
                video.srcObject = null;
            } catch (e) {
            }


            // Load the video to stop playback.
            video.load();

        }
        return;
    }

    // We can only set a new source when the stream becomes active.
    whenStreamIsActive(function getStream() {
        return stream;
    }, setSrc);
    function setSrc() {
        console.log('adding active playback stream');
        try {
            video.srcObject = stream;
        }
        catch (e) {
            // Some platforms don't support srcObject,
            // so we need to create a URL and set it instead.
            var url = (window.URL || window.webkitURL);
            video.src = url ? url.createObjectURL(stream) : stream;
        }
        $(video).prop("controls", true);//yarek
        //video.setAttribute('controls', true);
    }
}

// This starts a new client for:
// kind: publish | subscribe
// session: an identifier for the specific session within the socketMediaSoup.io connection
// streamid: the name used to correlate publishers with subscribers
function pubsub(kind, session, streamid) {
    // We return a promise that resolves with a {room, peers} object on success
    // rejects on error.
    return new Promise(function executor(resolve, reject) {
        // Issue the MS_START request for a new request id.
        pending[++reqid] = onPubsub;
        errors[reqid] = reject;
        sendMS({type: 'MS_START', payload: {kind, streamid}, meta: {id: reqid, session}});

        function onPubsub(payload) {
            // We received the response to MS_START.
            // Create a new MediaSoup client room for this session.
            var room = roomsMS[session] = new ms.Room({
                requestTimeout: 8000, // milliseconds timeout
                transportOptions: TRANSPORT_OPTIONS, // any special options
            });

            // Hook up the room's requests to this socketMediaSoup.io session.
            room.on('request', function onRequest(request, callback, errback) {
                // Create a new request, and set the callback
                pending[++reqid] = callback;
                // and error callback.
                errors[reqid] = errback;
                // Then send the request to the pubsub serverr.
                sendMS({type: 'MS_SEND', payload: request, meta: {id: reqid, session}})
            });
            // Hook up the room's notifications to this socketMediaSoup.io sessione
            room.on('notify', function onNotification(notification) {
                // Create a new notification (with no callbacks).
                sendMS({type: 'MS_SEND', payload: notification, meta: {notification: true, session}});
            });

            // Generate a random peerName, or "publisher" if we are the publisher.
            var peerName = kind === 'publish' ? 'publisher' : '' + Math.random();

            // Issue the join request via the MediaSoup client API, since it's all wired up.
            room.join(peerName)
                .then(function onJoin(peers) {
                    // We got the response, which are the peers, so resolve the pubsub promise.
                    resolve({room, peers});
                })
                // Join failures result in rejecting the pubsub promise.
                .catch(reject);
        }
    });
}


// Create the click handler for the start subscribing button.
function playStream(streamid, $video) {
    // Start a new session.
    var session = ++lastSession;
    var video = $video;


    // Start a pubsub session for that stream.
    pubsub('subscribe', session, streamid)
        .then(function onSubscribe(ps) {
            // We got a pubsub response, consisting of a Room and Peers.
            var room = ps.room;

            // Create a new transport we will use to receive from this publisher.
            var transport = room.createTransport('recv');

            // The server will only send us a single publisher.
            // Stream it if it is new...
            room.on('newpeer', function newPeer(peer) {
                console.log('New peer detected:', peer.name);
                setVideoSource(video[0], startRecvStream(peer));
            });

            // ... or if it already exists.
            if (ps.peers[0]) {
                console.log('Existing peer detected:', ps.peers[0].name);
                setVideoSource(video[0], startRecvStream(ps.peers[0]));
            } else {
                // Let them know that nobody is publishing yet.
                // This color is just a little gray to distinguish from black.
            }

            // Helper to start receiving a stream from a peer.
            function startRecvStream(peer) {
                var stream = new MediaStream();
                // Add consumers that are added later...
                peer.on('newconsumer', addConsumer);
                peer.on('closed', function closedPeer() {
                    // Reset the video source when close.d
                    setVideoSource(video);
                });

                // ... as well as adding the consumers that were already present.
                for (var i = 0; i < peer.consumers.length; i++) {
                    addConsumer(peer.consumers[i]);
                }

                // Helper to add a consumer to the stream.
                function addConsumer(consumer) {
                    if (!consumer.supported) {
                        // Cannot stream this media.
                        console.log('consumer', consumer.id, 'not supported');
                        return;
                    }

                    // Receive the consumer on our transport.
                    consumer.receive(transport)
                        .then(function receiveTrack(track) {
                            // We got a new track, so add it to the stream.
                            console.log('consuming', track.id);
                            remoteFeeds.set(streamid, session);
                            stream.addTrack(track);

                            // We have to reset the video source to make
                            // sure the track is added on all platforms.
                            setVideoSource(video, stream);

                            // On the close of the track...
                            consumer.on('close', function closeConsumer() {
                                // ... remove the old track.
                                console.log('removing the old track', track.id);
                                stream.removeTrack(track);

                                // Reset the video source to make the
                                // track stop playing on all platforms.
                                setVideoSource(video, stream);
                            });
                        })
                        .catch(function onError(e) {
                            // We got an error, so log it.
                            console.log('Cannot add track', e);
                        });
                }

                // Return the stream we created.
                return stream;
            }
        })
        .catch(function onError(e) {
            // An error in the pubsub protocol.
            alert('Cannot subscribe to streamid ' + streamid + ': ' + e);
        });
}


// Begin the publishing process.
function startPublish() {
    // We're starting.
    webrtcPublished = jQuery.Deferred();
    publishing = true;
    if (!gumStream) {
        // Need to getUserMedia.
        var constraints = {};
        constraints.audio = true;
        constraints.video = VIDEO_CONSTRAINTS;
        navigator.mediaDevices.getUserMedia(constraints)
            .catch(function firstErrorCallback(e) {
                // Needed on some iOS Safaris, which reject our
                // video constraints.
                // Delete the dimensions and try again.
                // FIXME: Maybe fps will also cause problems?
                delete constraints.video.width;
                delete constraints.video.height;
                return navigator.mediaDevices.getUserMedia(constraints);
            })
            .then(function successCallback(stream) {
                // We got the media, so track it.
                gumStream = stream;

                // Apply the mute setting.
                mute(pubmuted);

                // When the gumStream is ready, go on to activate it.
                whenStreamIsActive(function getGumStream() {
                    webrtcPublished.resolve(true);
                    return gumStream;
                }, onGumActive);
            })
            .catch(function errorCallback(e) {
                // Let the user know we failed.
                webrtcPublished.resolve(false);
                //alert('Error getting media (error code ' + e + ')');
                bootbox.alert("Cannot access webcam");
                //alert('Cannot publish to streamid ' + streamid + ': ' + e);
                jQuery.event.trigger({
                    type: 'doNotGetMyStreamId',
                    id: chatHTML5.myUser.id,
                });
            });
    }
    else {
        // We already have the user media, so go on to activate it.
        whenStreamIsActive(function getGumStream() {
            return gumStream;
        }, onGumActive);
    }


    function onGumActive() {

        console.log('adding active getUserMedia stream');

        // Change the background to let them know it is captured.

        // Get ready to handle the start of the video stream.
        myVideo.onplay = onPlay;

        // Set the video source.
        setVideoSource(myVideo, gumStream);

        function onPlay() {
            // We're ready, so remove the callback.
            myVideo.onplay = null;

            // Now that we have something to show, begin streaming.
            // Wait a little bit, so the video starts playing on Chrome.
            setTimeout(doPublish, 500);
            function doPublish() {
                // FIXME: Again, you may want a different way to get a streamid
                // for the publisher.
                var streamid = chatHTML5.myUser.id;
                if (streamid === null) {
                    return;
                }
                //streamid = streamid.trim().toLowerCase();

                // Keep note of the new publisher's session.
                pubsession = ++lastSession;

                // Tell them we can stop.


                // Begin the pubsub client for this session and streamid.
                pubsub('publish', pubsession, streamid)
                    .then(function onPublish(ps) {
                        // Get our MediaSoup client Room.
                        var room = ps.room;

                        // Create a transport for sending the stream.
                        var transport = room.createTransport('send');

                        // Send it now.
                        startSendStream(gumStream);
                        function startSendStream(stream) {
                            // Start connections when our stream is ready.
                            whenStreamIsActive(function getStream() {
                                return stream;
                            }, doConnects);
                            function doConnects() {
                                if (!stream) {
                                    // No stream, so ignore.
                                    return;
                                }
                                // Produce all the tracks from our stream.
                                for (var track of
                                stream.getTracks()
                            )
                                {
                                    console.log('producing', track.id);
                                    // Create a producer for the track.
                                    var producer = room.createProducer(track);

                                    // Register it with our producer list for this publisher.
                                    producers.push(producer);
                                    jQuery.event.trigger({
                                        type: 'getMyStreamId',
                                        id: chatHTML5.myUser.id,
                                    });

                                    // Send the producer on the transport.
                                    producer.send(transport);
                                }
                            }
                        }
                    })
                    .catch(function onError(e) {
                        // Error with the pubsub client.
                        bootbox.alert("Cannot access webcam");
                        //alert('Cannot publish to streamid ' + streamid + ': ' + e);
                        jQuery.event.trigger({
                            type: 'doNotGetMyStreamId',
                            id: chatHTML5.myUser.id,
                        });
                    });
            }
        }
    }
}

// Just stop publishing the stream.
function stopPublish() {
    if (typeof myVideo=='undefined') {
        return;
    }
    // We're stopping.
    publishing = false;

    // Stop playing our video.
    setVideoSource(myVideo);

    // Close all of the publisher's producers.
    while (producers[0]) {
        producers[0].close();
        producers.shift();
    }

    // Close the user media stream.
    var stream = gumStream;
    gumStream = undefined;
    if (!stream) {
        // No user stream, just return.
        return;
    }

    if (stream.stop) {
        // There's a stop method, so use it.
        stream.stop();
    } else if (stream.getTracks) {
        // We need to stop each of the tracks individually.
        for (var track of stream.getTracks())
        {
            track.stop();
        }
    }
}


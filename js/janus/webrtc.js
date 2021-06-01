var janus = null;
var sfu = null;
var remoteFeeds = new Map();
var opaqueId = "videoroomtest-"+Janus.randomString(12);
var myroom = 1234;

var mystream = null;
var myvideoDOMContainer;
var sfuIsReadyPromise = jQuery.Deferred();
var webrtcPublished;

function startJanus(server, myuser, turnServer, debug) {
    // Initialize the library (all console debuggers enabled)
    switch (chatHTML5.config.codec) {
        case 'h264':
            myroom = 1234;
            break;
        case 'vp8':
            myroom = 5678;
            break;
        case 'vp9':
            myroom = 8910;
            break;
    }

    debug = true;
    if (!server) {
        server = "https://" + window.location.hostname + ":8089/janus";
    }
    Janus.init({
        debug: debug, callback: function () {
            var iceServers = [];
            if (!turnServer || !turnServer.urls) {
                turnServer = {iceTransportPolicy : 'all'};
            } else {
                turnServer.iceTransportPolicy = 'relay';
                iceServers.push({urls: turnServer.urls, username:turnServer.username, credential:turnServer.credential});
            }

            // Make sure the browser supports WebRTC
            if (!Janus.isWebrtcSupported()) {
                console.log("No WebRTC support... ");
                return;
            }
            // Create session

            janus = new Janus(
                {
                    server: server,
                    iceTransportPolicy: turnServer.iceTransportPolicy,
                    iceServers :iceServers,
                    success: function () {
                        // Attach to video room test plugin
                        //console.log('SUCCESS');

                        janus.attach(
                            {
                                plugin: "janus.plugin.videoroom",
                                opaqueId: opaqueId,
                                success: function (pluginHandle) {
                                    sfu = pluginHandle;
                                    Janus.log("Plugin attached! (" + sfu.getPlugin() + ", id=" + sfu.getId() + ")");
                                    // Prepare the username registration
                                    var  event = new Event("janusReady");
                                    document.dispatchEvent(event);
                                    var register = { "request": "join", "room": myroom, "ptype": "publisher", "display": myuser.username, id: parseInt(myuser.id) };
                                    sfu.send({"message": register})
                                    sfuIsReadyPromise.resolve(true);

                                },
                                error: function (error) {
                                    Janus.error("  -- Error attaching plugin...", error);
                                    console.log("Error attaching plugin... " + error);
                                },
                                consentDialog: function (on) {
                                    Janus.debug("Consent dialog should be " + (on ? "on" : "off") + " now");

                                },
                                mediaState: function (medium, on) {
                                    Janus.log("Janus " + (on ? "started" : "stopped") + " receiving our " + medium);
                                },
                                webrtcState: function (on) {
                                    Janus.log("Janus says our WebRTC PeerConnection is " + (on ? "up" : "down") + " now");

                                },
                                onmessage: function (msg, jsep) {
                                    Janus.debug(" ::: Got a message (publisher) :::");
                                    Janus.debug(msg);
                                    var event = msg["videoroom"];
                                    Janus.debug("Event: " + event);
                                    if (event != undefined && event != null) {
                                        if (event === "joined") {
                                            // Publisher/manager created, negotiate WebRTC and attach to existing feeds, if any
                                            var myid = msg["id"];
                                            Janus.log("Successfully joined room " + msg["room"] + " with ID " + myid);
                                            //publishOwnFeed(true);
                                            // Any new feed to attach to?
                                            if (msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                                var list = msg["publishers"];
                                                Janus.debug("Got a list of available publishers/feeds:");
                                                Janus.debug(list);
                                                for (var f in list) {
                                                    var id = list[f]["id"];
                                                    var display = list[f]["display"];
                                                    var audio = list[f]["audio_codec"];
                                                    var video = list[f]["video_codec"];
                                                    Janus.debug("  >> [" + id + "] " + display + " (audio: " + audio + ", video: " + video + ")");
                                                    console.log('1Streamid:', id, audio, video);
                                                    //playStream(id, display, audio, video);
                                                }
                                            }
                                        } else if (event === "destroyed") {
                                            // The room has been destroyed
                                            Janus.warn("The room has been destroyed!");
                                            console.log("The room has been destroyed");
                                        } else if (event === "event") {
                                            // Any new feed to attach to?
                                            if (msg["publishers"] !== undefined && msg["publishers"] !== null) {
                                                var list = msg["publishers"];
                                                Janus.debug("Got a list of available publishers/feeds:");
                                                Janus.debug(list);
                                                for (var f in list) {
                                                    var id = list[f]["id"];
                                                    var display = list[f]["display"];
                                                    var audio = list[f]["audio_codec"];
                                                    var video = list[f]["video_codec"];
                                                    Janus.debug("  >> [" + id + "] " + display + " (audio: " + audio + ", video: " + video + ")");
                                                    console.log('2Streamid:', id, audio, video);
                                                }
                                            } else if (msg["leaving"] !== undefined && msg["leaving"] !== null) {
                                                // One of the publishers has gone away?
                                                var leaving = msg["leaving"];
                                                Janus.log("Publisher left: " + leaving);
                                                var remoteFeed = null;

                                            } else if (msg["unpublished"] !== undefined && msg["unpublished"] !== null) {
                                                // One of the publishers has unpublished?
                                                var unpublished = msg["unpublished"];
                                                Janus.log("Publisher left: " + unpublished);
                                                if (unpublished === 'ok') {
                                                    // That's us
                                                    var event = new Event("janusUnPublished");
                                                    document.dispatchEvent(event);
                                                    sfu.hangup();
                                                    return;
                                                }

                                            } else if (msg["error"] !== undefined && msg["error"] !== null) {
                                                if (msg["error_code"] === 426) {
                                                    // This is a "no such room" error: give a more meaningful description
                                                    console.log('the one this demo uses as a test room');
                                                } else {
                                                    console.log(msg["error"]);
                                                }
                                            }
                                        }
                                    }
                                    if (jsep !== undefined && jsep !== null) {
                                        Janus.debug("Handling SDP as well...");
                                        Janus.debug(jsep);
                                        sfu.handleRemoteJsep({jsep: jsep});
                                        // Check if any of the media we wanted to publish has
                                        // been rejected (e.g., wrong or unsupported codec)
                                        var audio = msg["audio_codec"];
                                        if (mystream && mystream.getAudioTracks() && mystream.getAudioTracks().length > 0 && !audio) {
                                            // Audio has been rejected
                                            console.log("Our audio stream has been rejected, viewers won't hear us");
                                        }
                                        var video = msg["video_codec"];
                                        if (mystream && mystream.getVideoTracks() && mystream.getVideoTracks().length > 0 && !video) {
                                            // Video has been rejected
                                            console.log("Our video stream has been rejected, viewers won't see us");
                                            // Hide the webcam video

                                        }
                                    }
                                },
                                onlocalstream: function (stream) {
                                    Janus.debug(" ::: Got a local stream :::");
                                    mystream = stream;
                                    jQuery.event.trigger({
                                        type: 'getMyStreamId',
                                        id: chatHTML5.myUser.id,
                                    });
                                    var videoTracks = mystream.getVideoTracks();
                                    if (videoTracks === null || videoTracks === undefined || videoTracks.length === 0) {
                                        myvideoDOMContainer.html('No webcam available');
                                    } else {
                                        if (jQuery('#myVideo').length === 0) {
                                            var el = '\
                                            <video id="myVideo" width="100%" height="100%" autoplay playsinline muted="muted"></video>\
                                            <input id="myAudioCheckBox" type="checkbox" data-onstyle="info" data-style="ios" data-offstyle="default" data-onstyle="default" data-size="mini" checked data-toggle="toggle" data-on="<i class=\'fa fa-volume-up\'></i> On" data-off="<i class=\'fa fa-volume-off\'></i> Off">\
                                            ';
                                            myvideoDOMContainer.append(el);
                                            $('#myAudioCheckBox').bootstrapToggle();
                                            $('#myAudioCheckBox').parent().addClass('myAudioCheckBox');

                                        }
                                        Janus.attachMediaStream(jQuery('#myVideo').get(0), mystream);
                                        jQuery('#myVideo').get(0).muted = 'muted';
                                        var event = new Event("janusPublished");
                                        document.dispatchEvent(event);
                                    }

                                },
                                onremotestream: function (stream) {
                                    // The publisher stream is sendonly, we don't expect anything here
                                },
                                oncleanup: function () {
                                    Janus.log(" ::: Got a cleanup notification: we are unpublished now :::");
                                    mystream = null;
                                    //publishOwnFeed(true);
                                }
                            });
                    },
                    error: function (error) {

                        Janus.error(error);
                        console.log(error);
                    },
                    destroyed: function () {

                        window.location.reload();
                    }
                });

        }
    });
}

function publishStream(_myvideoDOMContainer, streamIfWatched, filenameRecord){
    webrtcPublished = jQuery.Deferred();
    myvideoDOMContainer = _myvideoDOMContainer;
    jQuery.when( sfuIsReadyPromise ).done(function ( res ) {
        publishOwnFeed(true, streamIfWatched, filenameRecord);
    });
}

function startRecordVideo(filenameRecord) {
    publishOwnFeed(true, false, filenameRecord);
}

function stopRecordVideo() {
    unpublishOwnFeed();
    setTimeout(function() {
        publishOwnFeed(true, false, false);
    }, 3000)
}

function publishOwnFeed(useAudio, streamIfWatched, filenameRecord) {

    console.log('publishOwnFeed');
    // Publish our stream
    sfu.createOffer(
        {
            // Add data:true here if you want to publish datachannels as well
            media: { audioRecv: false, videoRecv: false, audioSend: useAudio, videoSend: true },	// Publishers are sendonly
            // If you want to test simulcasting (Chrome and Firefox only), then
            // pass a ?simulcast=true when opening this demo page: it will turn
            // the following 'simulcast' property to pass to janus.js to true
            simulcast: false,
            success: function(jsep) {
                console.log('publishing');
                webrtcPublished.resolve(true);
                Janus.debug("Got publisher SDP!");
                Janus.debug(jsep);
                var publish = { "request": "configure", audio: true, video: true };
                if (filenameRecord) {
                    publish.filename = filenameRecord;
                    publish.record = true;
                } else {
                    publish.filename = ''
                    publish.record = false;
                }
                // You can force a specific codec to use when publishing by using the
                // audiocodec and videocodec properties, for instance:
                // 		publish["audiocodec"] = "opus"
                // to force Opus as the audio codec to use, or:
                // 		publish["videocodec"] = "vp9"
                // to force VP9 as the videocodec to use. In both case, though, forcing
                // a codec will only work if: (1) the codec is actually in the SDP (and
                // so the browser supports it), and (2) the codec is in the list of
                // allowed codecs in a room. With respect to the point (2) above,
                // refer to the text in janus.plugin.videoroom.cfg for more details
                chatHTML5.myUser.jsep = jsep;
                if (1==1 || streamIfWatched!='1') {
                    console.log('SendMessage PUBLISH !');
                    sfu.send({"message": publish, "jsep": jsep});
                }
            },
            error: function(error) {
                Janus.error("WebRTC error:", error);
                webrtcPublished.resolve(false);
                if (useAudio) {
                    publishOwnFeed(false);
                } else {
                    console.log("WebRTC error... " + JSON.stringify(error));
                    publishOwnFeed(true);
                }
            }
        });
}

function toggleMute() {
    var muted = sfu.isAudioMuted();
    Janus.log((muted ? "Unmuting" : "Muting") + " local stream...");
    if(muted)
        sfu.unmuteAudio();
    else
        sfu.muteAudio();
    muted = sfu.isAudioMuted();

}

function stopPublishStream() {
    unpublishOwnFeed();
}

function unpublishOwnFeed() {
    // Unpublish our stream
    var unpublish = { "request": "unpublish" };
    sfu.send({"message": unpublish});
}


function playStream(id, videoElementId, muted, video) {
    // A new feed has been published, create a new plugin handle and attach to it as a subscriber
    id = parseInt(id);
    console.log('playStream', id);
    var remoteFeed = null;
    janus.attach({
            plugin: "janus.plugin.videoroom",
            opaqueId: opaqueId,
            success: function(pluginHandle) {
                remoteFeeds.set(id, pluginHandle);
                remoteFeed = pluginHandle;

                remoteFeed.simulcastStarted = false;
                Janus.log("Plugin attached! (" + remoteFeed.getPlugin() + ", id=" + remoteFeed.getId() + ")");
                Janus.log("  -- This is a subscriber");
                // We wait for the plugin to send us an offer
                var listen = { "request": "join", "room": myroom, "ptype": "subscriber", "feed": id };
                // In case you don't want to receive audio, video or data, even if the
                // publisher is sending them, set the 'offer_audio', 'offer_video' or
                // 'offer_data' properties to false (they're true by default), e.g.:
                // 		listen["offer_video"] = false;
                // For example, if the publisher is VP8 and this is Safari, let's avoid video
                /*debugger;
                 if(video !== "h264" && Janus.webRTCAdapter.browserDetails.browser === "safari") {
                 if(video)
                 video = video.toUpperCase()
                 console.log("Publisher is using " + video + ", but Safari doesn't support it: disabling video");
                 listen["offer_video"] = false;
                 }*/
                remoteFeed.videoCodec = video;
                remoteFeed.send({"message": listen});

            },
            error: function(error) {
                Janus.error("  -- Error attaching plugin...", error);
                console.log("Error attaching plugin... " + error);
            },
            onmessage: function(msg, jsep) {
                Janus.debug(" ::: Got a message (subscriber) :::");
                Janus.debug(msg);
                var event = msg["videoroom"];
                Janus.debug("Event: " + event);
                if(msg["error"] !== undefined && msg["error"] !== null) {
                    console.log(msg["error"]);
                } else if(event != undefined && event != null) {
                    if(event === "attached") {
                        // Subscriber created and attached

                        remoteFeed.rfid = msg["id"];
                        remoteFeed.rfdisplay = msg["display"];
                        Janus.log("Successfully attached to feed " + remoteFeed.rfid + " (" + remoteFeed.rfdisplay + ") in room " + msg["room"]);

                    } else if(event === "event") {
                        // Check if we got an event on a simulcast-related event from this publisher
                        var substream = msg["substream"];
                        var temporal = msg["temporal"];
                        if((substream !== null && substream !== undefined) || (temporal !== null && temporal !== undefined)) {
                            // We just received notice that there's been a switch, update the buttons
                        }
                    } else {
                        // What has just happened?
                    }
                }
                if(jsep !== undefined && jsep !== null) {
                    Janus.debug("Handling SDP as well...");
                    Janus.debug(jsep);
                    // Answer and attach
                    remoteFeed.createAnswer(
                        {
                            jsep: jsep,
                            // Add data:true here if you want to subscribe to datachannels as well
                            // (obviously only works if the publisher offered them in the first place)
                            media: { audioSend: false, videoSend: false },	// We want recvonly audio/video
                            success: function(jsep) {
                                Janus.debug("Got SDP!");
                                Janus.debug(jsep);
                                var body = { "request": "start", "room": myroom };
                                remoteFeed.send({"message": body, "jsep": jsep});
                            },
                            error: function(error) {
                                Janus.error("WebRTC error:", error);
                                console.log("WebRTC error... " + JSON.stringify(error));
                            }
                        });
                }
            },
            webrtcState: function(on) {
                Janus.log("Janus says this WebRTC PeerConnection (feed #" + remoteFeed.rfindex + ") is " + (on ? "up" : "down") + " now");
            },
            onlocalstream: function(stream) {
                // The subscriber stream is recvonly, we don't expect anything here
            },
            onremotestream: function(stream) {
                Janus.debug("Remote feed #" + remoteFeed.rfindex);
                Janus.debug("Remote feed #" + remoteFeed.rfid);
                if(jQuery('#remotevideo'+remoteFeed.rfid).length === 0) {
                    var mutedString = (muted)?'muted="muted"':'';
                    //console.log('onremotestream');
                    var temp = sprintf('<video id="remotevideo%s" autoplay="autoplay" width="100%%" height="100%%" playsinline="" controls  data-id="%s" %s/>',
                        remoteFeed.rfid, remoteFeed.rfid, mutedString);
                    videoElementId.append(temp);
                }

                Janus.attachMediaStream(jQuery('#remotevideo' + remoteFeed.rfid).get(0), stream);


                var videoTracks = stream.getVideoTracks();
                if(videoTracks === null || videoTracks === undefined || videoTracks.length === 0) {
                    // No remote video
                }

            },
            oncleanup: function() {
                Janus.log(" ::: Got a cleanup notification (remote feed " + id + ") :::");

            }
        });
}

function stopStream(streamid) {
    var sfu2 = remoteFeeds.get(streamid);
    if (sfu2) {
        var leaveRequest = {"request": "leave"};
        sfu2.send({"message": leaveRequest});
    }
}

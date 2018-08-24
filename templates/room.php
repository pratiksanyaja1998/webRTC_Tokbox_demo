

<div id="formBoxLogin">
    <div class="panel panel-default">
        <div class="panel-heading text-center"><h3><b>Join Sesion</b></h3></div>
        <div class="panel-body">
            <form onsubmit="getSessionData(event)">
                    <div class="form-group">
                        <label for="inputname">Display Name:</label>
                        <input type="text" class="form-control" placeholder="Enter Name (Name to display)" id="inputname" name="name">
                    </div>

                    <?php 
                        
                        if($ispassword){
                            
                    ?>
                        <div class="form-group" >
                            <label for="inputpass">Password:</label>
                            <input type="password" class="form-control" placeholder="Enter password" id="inputpass" name="password">
                            <span class="error"></span>
                        </div>

                    <?php

                        }else{

                    ?>

                            <span class="error"></span>
                            <input type="hidden" class="form-control" placeholder="Enter password" id="inputpass" name="password">
                    <?php

                        }
                    ?>
                    <div class="form-group" >
                        
                        <button type="submit"  class="btn btn-primary">Start Session</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<div class="sessionpanel" style="padding:15px" >
        <div class="text-center"><img class="" width="65px" src="./image/team.png" /></div>
        <div id="publisher"></div>
        <div class="form-group text-center" >
            <label class="mt-15">Session Link:</label>
            <input type="text" id="sessionLink" class="form-control linkInput" value="<?php echo $baseurl.'/'.$uniqid; ?>">
            <label class="mt-15">Session ID:</label>
            <input type="text" id="sessionID" class="form-control linkInput" value="<?php echo $uniqid; ?>" >
        </div>                   
</div>


<div class="" >
    <div id="subscribers"></div>
</div>


<div id="textchat">
        <div id="history">
        <div class="msgheader text-center">
            <img class="img img-responsive" width="50px" height="50px" src="./image/messageblack.png" style="margin: auto;" >        
            <p style="margin-top:10px"><b>GetCommunicate Messager Service</b></p>
            <hr/>
        </div>
        </div>
        <form id="msgForm">
                <input type="text" class="form-control" placeholder="Input your message here" id="msgTxt" >
        </form>
</div>

    
<script type="text/javascript">

    const SAMPLE_SERVER_BASE_URL ="<?php echo $baseurl; ?>";
    var apiKey = '<?php echo $apiKey; ?>';
    var sessionId = '<?php echo $sessionId; ?>';
    var uniqid = '<?php echo $uniqid; ?>';
    var token = '<?php echo $token; ?>';
    var name = '<?php echo $name; ?>';
    var subscribers = 0;
    var isMobile = window.matchMedia("only screen and (max-width: 768px)");
    var connectionCount = 0;

    function initializeSession(){
        var session = OT.initSession(apiKey, sessionId);
        var publisherOptions = { name:name ,width: '100%' };
        var publisher = OT.initPublisher('publisher',publisherOptions, function initCallback(initErr) {
                if (initErr) {
                console.error('There was an error initializing the publisher: ', initErr.name, initErr.message);
                return;
                }
                console.log("publisher is init.")
        });
 
        session.on("connectionCreated", function(event) {
            connectionCount++;
            console.log(connectionCount)
        });
        
        session.on("connectionDestroyed", function(event) {
            connectionCount--;
            console.log(connectionCount)
        });

        session.on('streamCreated', function(event) {

            var subscribeOptions = {
                insertMode: 'append', width: '100%', height: '100%' 
            }

            session.subscribe(event.stream, 'subscribers',subscribeOptions , function(error) {
                if (error) {
                console.error('Failed to subscribe', error);
                }else if(!isMobile.matches){
                    subscribers++;
                    if(subscribers==2){
                        $('.OT_subscriber').css("width", "50%");
                        $('.OT_subscriber').css("height", "100%");
                    } else if(subscribers>2){
                        $('.OT_subscriber').css("width", "50%");
                        $('.OT_subscriber').css("height", "50%");
                    }
                }
                if(isMobile.matches){
                    subscribers++;
                    var pxs= subscribers*250;
                    $('#textchat').css("margin-top",pxs.toString()+"px");
                }
            });
        });


        session.on("streamDestroyed", function(event) {
            subscribers--; 
            if(!isMobile.matches){
                if(subscribers==2){
                    $('.OT_subscriber').css("width", "50%");
                    $('.OT_subscriber').css("height", "100%");
                } else if(subscribers>2){
                    $('.OT_subscriber').css("width", "50%");
                    $('.OT_subscriber').css("height", "50%");
                }else if(subscribers==1){
                    $('.OT_subscriber').css("width", "100%");
                    $('.OT_subscriber').css("height", "100%");
                }
            }
            if(isMobile.matches){
                var pxs= subscribers*250;
                $('#textchat').css("margin-top",pxs.toString()+"px");
            }
        });

        session.connect(token, function(error) {
            if (error) {
                console.error('Failed to connect', error);
            } else {
                session.publish(publisher, function(error) {
                    if (error) {
                        console.error('Failed to publish', error);
                    }
                });
            }
        });

        var msgHistory = document.querySelector('#history');
        session.on('signal:msg', function signalCallback(event) {

                // if(event.data.name!=name){
                //     var label = document.createElement('span');
                //     label.textContent = event.data.name; 
                //     label.className =  'labelmsgname';
                //     msgHistory.appendChild(label);
                //     label.scrollIntoView();
                // }
                var msg = document.createElement('span');
                msg.textContent = (event.data.name==name )? event.data.msg : event.data.name + " : "+event.data.msg;
                msg.className = event.from.connectionId === session.connection.connectionId ? 'mine' : 'theirs';
                msgHistory.appendChild(msg);
                msg.scrollIntoView();
            }
        );

        var form = document.querySelector('#msgForm');
        var msgTxt = document.querySelector('#msgTxt');

        form.addEventListener('submit', function submit(event) {
            
            event.preventDefault();

            session.signal({
                    type: 'msg',
                    data: {'name' :name, 'msg': msgTxt.value}
                }, function signalCallback(error) {
                    if (error) {
                    console.error('Error sending signal:', error.name, error.message);
                    } else {
                    msgTxt.value = '';
                    }
                });
        });

    }

    $("#formBoxLogin").hide();

    if(token!=""){
        initializeSession();
    }else{
        $("#formBoxLogin").show();
    }
    
    function getSessionData(event){
        event.preventDefault();

        name = document.getElementById("inputname").value;
        password = document.getElementById("inputpass").value;

        $.post( SAMPLE_SERVER_BASE_URL + '/gettoken.php',
            {'name': name,'password':password,'sessionid':uniqid},
            function(result){
                var data =JSON.parse(result);

                if(data.error==0){
                    token=data.token;
                    $("#formBoxLogin").hide();
                    initializeSession();
                }else{
                    $('.error').text(data.error);
                }
        
            }
        ).fail(function(response) {
            console.log('Error:  internal server error code is 5252.' );
        });
    }

    function coppySessionLink() {
        /* Get the text field */
        var copyText = document.getElementById("sessionLink");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");

    } 


    $(document).ready(function(){

        $("#textchat").show();
        $("#messageimg").hide();
    
        $("#messageimg").click(function(){
            $("#textchat").show();
            $("#messageimg").hide();
        });

        $("#closeicon").click(function(){
            $("#textchat").hide();
            $("#messageimg").show();
        });

    });

</script>

<div class="container" >


    <div class="row">
        <div class="col text-center" style="margin:50px">
            <h2><b>Create Video Communication Session</b></h2>
        </div>
    </div>

    <div class="row indexFormRow">

        <div class="col-sm-6">

            <div class="panel panel-default">
                <div class="panel-heading text-center"><h3><b>Create Session</b></h3></div>
                <div class="panel-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="newsessionform" onsubmit="validateFormNewSession(event)">
                            <div class="form-group">
                                <label >Display Name:</label>
                                <input type="text" id="nameNewInput" class="form-control" placeholder="Enter Name (Name to display)" name="name">
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" id="ispassckbox" name="ispassword"> Enable Passoword to join session</label>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password:</label>
                                <input type="password" id="passInput"  class="form-control" id="pwd" placeholder="Enter password" name="password">
                            </div>
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" checked="checked" name="type" value="-1">Multi User Session</label>
                                <label class="radio-inline"><input type="radio" name="type" value="1">1 to 1 Private Session</label>
                            </div>
                            <span class="error" id="newerror"></span><br/>
                            <button type="submit" class="btn btn-primary">Start Session</button>
                        </form>
                </div>
            </div>

        </div>

        <div class="col-sm-6">

            <div class="panel panel-default">
                <div class="panel-heading text-center"><h3><b>Join Session</b></h3></div>
                <div class="panel-body">
                    <form method="get" action="<?php echo "/room.php"; ?>" onsubmit="validateFormJoinSession(event)">
                        <div class="form-group">
                            <label for="email">Session Id:</label>
                            <input type="text" class="form-control" placeholder="Session ID" name="sessionid" id="sessionidInput">
                        </div>
                        <div class="form-group">
                                <label for="email">Display Name:</label>
                                <input type="text" class="form-control" placeholder="Name (name to display)" id="namejoinInupt" name="name">
                        </div>
                        <span class="error" id="joinerror"></span><br/>
                        <button type="submit" class="btn btn-primary">Join Session</button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>


<script>

    function validateFormNewSession(event){

        var name = $('#nameNewInput').val();
        var ispass = $('#ispassckbox').val();
        var password = $('#passInput').val();

        $('#newerror').hide();

        if(name==""){
            $('#newerror').text( "Please enter your name.");
            event.preventDefault();
            $('#newerror').show();
        }else if($('#ispassckbox').prop('checked')){
            if(password==""){
                $('#newerror').text("Please enter your password.");
                event.preventDefault();
                $('#newerror').show();
            }
        }else if(password!=""){
            if(!$('#ispassckbox').prop('checked')){
                $('#newerror').text("Please check enable password for session.");
                $('#newerror').show();
                event.preventDefault();
            }
        }
    }   

    function validateFormJoinSession(event){

        var name = $('#namejoinInupt').val();
        var sessionid = $('#sessionidInput').val();
        

        $('#joinerror').hide();

        if(name==""){
            $('#joinerror').text( "Please enter your name.");
            event.preventDefault();
            $('#joinerror').show();
        }else if(sessionid==""){
                $('#joinerror').text("Please enter your session ID.");
                event.preventDefault();
                $('#joinerror').show();
            
        }
        
    }   

</script>
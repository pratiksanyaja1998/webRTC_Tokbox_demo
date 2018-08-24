<!DOCTYPE html>
<html>
<title>GetCommunicate.com</title>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="description is hear">
    <meta name="keywords" content="WebRTC, getcommunicate">

    <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
    <!-- Polyfill for fetch API so that we can fetch the sessionId and token in IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js" charset="utf-8"></script>

    <script src="./js/jquery.min.js"></script>

    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">

</head>
<body>


<?php

    require "./vendor/autoload.php";
    require "./lib/config.php";

    use OpenTok\OpenTok;
    use OpenTok\MediaMode;

    $name = $password = $type = $ispassword  = $token = $sessionId = $uniqid ="";
    $data = array();

    $opentok = new OpenTok($apiKey, $apiSecret);

    if(isset($_GET["sessionid"]) && isset($_GET["name"])){
           
        $uniqid = $_GET["sessionid"];
        $name = $_GET["name"];
        
        if(file_exists('./json/'.$uniqid.'.json') ) {
        
            $str = file_get_contents('./json/'.$uniqid.'.json');
        
            $json = json_decode($str, true);

            $sessionId = $json['sessionId'];
    
            if($json['type']==1){
                if($json['count']<2){
                    $json['count']= $json['count']+1;
                    file_put_contents('./json/'.$uniqid.'.json', json_encode($json));
                    $token = $opentok->generateToken($sessionId);
                    require_once "./templates/room.php";
                }else{
                    echo "your room is full please contact your admin.";
                }
            }else{
                $token = $opentok->generateToken($sessionId);
                require_once "./templates/room.php";
            }
        
        }
        else {
        
            require_once "./templates/sessionvaliderror.php";
        
        }

    }elseif(isset($_GET["sessionid"])){

        $uniqid = $_GET["sessionid"];

        if(file_exists('./json/'.$uniqid.'.json') ) {
        
            $str = file_get_contents('./json/'.$uniqid.'.json');
            $json = json_decode($str, true);
            
            $ispassword=$json['ispassword'];

            $sessionId = $json['sessionId'];

            require_once "./templates/room.php";
        
        }else{

            require_once "./templates/sessionvaliderror.php";
        
        }

    }else{

        header('Location: /index.php');

    }

?>

</body>
</html>
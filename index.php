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

    $name = $password = $type = $ispassword  = $token = $sessionId = $uniqid = "";
    $data = array();

    $words = explode(' ', $_SERVER['REQUEST_URI']);
    $uniqid = trim($words[count($words) - 1], '/');
    
    $opentok = new OpenTok($apiKey, $apiSecret);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name=$_POST["name"];

        $type=$_POST["type"];
        $data['type'] = (int)$type;

        if(isset($_POST["ispassword"])){
            $ispassword=$_POST["ispassword"];
            $password=$_POST["password"];
            $data['ispassword'] = $ispassword;
            $data['password'] = $password;
        }else{
            $data['ispassword'] = false;
        }
        
        $session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));
        $sessionId = $session->getSessionId();

        $data['sessionId'] = $sessionId;
        $data['count'] = 1;

        $uniqid = uniqid();

        $token = $opentok->generateToken($sessionId);        

        $fp = fopen('./json/'.$uniqid.'.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);

        require_once "./templates/room.php";

    }else if($uniqid != ""){
        
        //room php file

        if(file_exists('./json/'.$uniqid.'.json') ) {
        
            $str = file_get_contents('./json/'.$uniqid.'.json');
            $json = json_decode($str, true);
            
            $ispassword=$json['ispassword'];

            $sessionId = $json['sessionId'];

            require_once "./templates/room.php";
        
        }else{

            require_once "./templates/sessionvaliderror.php";
        
        }
        
        //end room file
        
    }else{
        
        require_once "./templates/index.php";

    }

?>

</body>
</html>
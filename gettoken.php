<?php

    require "./vendor/autoload.php";
    require "./lib/config.php";

    use OpenTok\OpenTok;
    use OpenTok\MediaMode;

    $name = $password = $type = $ispassword  = $token = $sessionId = $uniqid = "";

    $opentok = new OpenTok($apiKey, $apiSecret);
    $data = array();
    $data['error'] = 0;
    $data['token'] = "";
    
    if( isset($_POST['sessionid']) && isset($_POST['name']) ){

        $uniqid = $_POST['sessionid'];

        $str = file_get_contents('./json/'.$uniqid.'.json');
        $json = json_decode($str, true);

        $sessionId = $json['sessionId'];

        if($json['ispassword']){
            
            $password = $json['password'];
            
            if($password===$_POST['password']){

                echo getToken($uniqid,$sessionId,$opentok);

            }else{

                $data['error'] = "Please enter valid password.";
                echo json_encode($data);
            }
            
        }else{

            // $token = $opentok->generateToken($sessionId);  

            echo getToken($uniqid,$sessionId,$opentok);

        }
        
    }else{

        $data['error'] = "Please check your link.";
                echo json_encode($data);
        // header('Location: /index.php');
    }

    function getToken($uniqid , $sessionId , $opentok){
        $data = array();
        $data['error'] = 0;
        try {
            $str = file_get_contents('./json/'.$uniqid.'.json');
        }
        catch (Exception $e) {
             $data['error'] = "please enter valid session id.";
        }
        $json = json_decode($str, true);
 
        if($json['type']==1){
            if($json['count']<2){
                $json['count']= $json['count']+1;
                file_put_contents('./json/'.$uniqid.'.json', json_encode($json));
                   $data['token'] = $opentok->generateToken($sessionId);
            }else{
                $data['error'] = "your room is full please contact your admin.";
            }
        }else{
            $json['count']= $json['count']+1;
            file_put_contents('./json/'.$uniqid.'.json', json_encode($json));
            $data['token'] = $opentok->generateToken($sessionId);
        }
        return json_encode($data);
    }

?>
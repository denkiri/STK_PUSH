<?php
include '../classes/Aouth.php';
$db = new DbHandler;
$response = array();

if (validate()) {
    $user=$db->login($_POST['email'],md5(sha1($_POST['password'])));

    if ($user != NULL) {
        $token = bin2hex(openssl_random_pseudo_bytes(64));

        if($db->updateToken($user["id"],$token)){
            $user['token'] = $token;

        $response["error"] = false;
        $response["status"] = 1;
        $response["profile"] = $user;
        $response["token"] = $token;
        $response["message"] = "User logged in succesfully";
        }else{
            $response["error"] = false;
            $response["status"] = 0;
            $response["profile"] = NULL;
            $response["token"] = NULL;
            $response["message"] = "Error Creating Token";
        }

    } else {
        $response["error"] = false;
        $response["status"] = 0;
        $response["profile"] = NULL;
        $response["token"] = NULL;
        $response["message"] = "No user is registered with those credentials";
    }


    echo json_encode($response);

}


function validate()
{
 $isOkay=true; 
 $response = array();
 $response['fields'] = array();
 if(!isset($_POST['email'])||$_POST['email'] == '' ){
  $validatevalidateisOkay=false;
  $temp['email'] = "Required|String";
}
 if(!isset($_POST['password'])||$_POST['password'] == '' ){
    $isOkay=false;
    $temp['password'] = "Required|String";
}
 if($isOkay){
    return true;
 }else{
    $response["error"] = true;
    $response["status"] = 0;
    $response["profile"] = NULL;
    $response["token"] = NULL;
    $response["message"] = "Some fields are missing";
    echo json_encode($response);

    return false;
 }



}
?>


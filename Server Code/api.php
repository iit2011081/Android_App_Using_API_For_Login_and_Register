<?php
//$body = file_get_contents("php://input");
//var_dump($body);
//$_POST = json_decode($body, true);
//var_dump($_POST);
//die();
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];
 
    require_once 'Utils.php';
    $db = new Utils();
 
    $response = [
                    "tag" => $tag, 
                    "error" => FALSE
                ];
 
    if ($tag == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
 
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) {
            $response["error"] = FALSE;
            $response["uid"] = $user["uid"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            echo json_encode($response);
        } else {
            $response["error"] = TRUE;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
    } else if ($tag == 'register') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($db->isUserExisted($email)) {
            $response["error"] = TRUE;
            $response["error_msg"] = "User already existed";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password);
            if ($user) {
                $response["error"] = FALSE;
                $response["uid"] = $user["uid"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                echo json_encode($response);
            } else {
                $response["error"] = TRUE;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknow 'tag' value. It should be either 'login' or 'register'";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter 'tag' is missing!";
    echo json_encode($response);
}
?>
<?php
include_once 'Core.php';
include_once '../php_jwt/src/JWT.php';
include_once '../php_jwt/src/ExpiredException.php';
include_once '../php_jwt/src/SignatureInvalidException.php';
include_once '../php_jwt/src/BeforeValidException.php';

use \Firebase\JWT\JWT;

$data = getRequestInfo();
$jwt = $data["jwt"];
// get jwt
//$jwt=isset($data->jwt) ? $data->jwt : "";
//echo $jwt;

// if jwt is not empty
if($jwt)
{
 
    // if decode succeed, show user details
    try 
    {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        // set response code
        http_response_code(200);
 
        // show user details
        echo json_encode(array(
            "message" => "Access granted.",
            "ret" => true,
            "data" => $decoded->data
        ));
       // $test = json_encode($decoded->data);
       // $retVal = '{"message" : "Access granted.","ret":true, "data" :"' . $test . '"}';
       // sendResultInfoAsJson($retVal);
    }
 
    // if decode fails, it means jwt is invalid
    catch (Exception $e)
    {
    
        // set response code
        http_response_code(401);
    
        // tell the user access denied  & show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "ret" => false,
            "error" => $e->getMessage()
        ));
    }
}
 
// error if jwt is empty will be here
// show error message if jwt is empty
else
{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied.","ret" => false, "time" => time()));
}





function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

?>
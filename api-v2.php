<?php

header_remove('Server');
header("Content-type: application/json");
require('assets/init.php');

$headers = array(
    'access_token' => 'access_token',
    'access-token' => 'access_token',
    'server_key' => 'server_key',
    'server-key' => 'server_key',
    'device_type' => 'device_type',
    'device-type' => 'device_type'
);

foreach($headers as $header => $parameter){
    $value = GetHeader($header);
    if($value){
        $_POST[$parameter] = $_GET[$parameter] = $value;
    }
}


$wo['loggedin'] = false;
$response_data  = array();
$error_code     = 0;
$error_message  = '';
$type           = (!empty($_GET['type'])) ? Secure($_GET['type'], 0) : false;

if (empty($type)) {
    $response_data = array(
        'api_status' => 404,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Error: 404 API Type not specified'
        )
    );
    $response_data['api_status'] = (int)$response_data['api_status'];
    http_response_code($response_data['api_status']);
    echo json_encode($response_data, JSON_PRETTY_PRINT);
    exit();
}

$api                        = "api/v2/endpoints/$type.php";

$pages_without_access_token = array(
    'add_reward_link',
    'get_reward_links',
    'update_reward_link',
    'delete_reward_link',
    'notifications',
    'add_fcm_token'
);
$pages_without_loggedin     = array(
    'add_reward_link',
    'get_reward_links',
    'update_reward_link',
    'delete_reward_link',
    'notifications',
    'add_fcm_token'
);

if (!file_exists($api)) {
    $response_data = array(
        'api_status' => 404,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Error: 404 API Type Not Found'
        )
    );
    $response_data['api_status'] = (int)$response_data['api_status'];
    http_response_code($response_data['api_status']);
    echo json_encode($response_data, JSON_PRETTY_PRINT);
    exit();
}
if (!in_array($type, $pages_without_access_token)) {
    if (empty($_GET['access_token'])) {
        $error_code    = 1;
        $error_message = 'Error: access_token is missing';
    }
}
if (!empty($_GET['access_token'])) {
    $get_user_id_from_access_token = ValidateAccessToken($_GET['access_token']);
    if (is_numeric($get_user_id_from_access_token) && $get_user_id_from_access_token > 0) {
        $wo['user'] = UserData($get_user_id_from_access_token);
        if (!empty($wo['user'])) {
            $wo['loggedin'] = true;
            if ($wo['user']['user_id'] < 0 || empty($wo['user']['user_id']) || !is_numeric($wo['user']['user_id'])) {
                $wo['loggedin'] = false;
            }
        }
    }
}
if (!in_array($type, $pages_without_loggedin)) {
    if ($wo['loggedin'] == false && !empty($_GET['access_token'])) {
        $error_code    = 2;
        $error_message = 'Invalid or expired access_token';
    } else if ($wo['loggedin'] == false) {
        $error_code    = 2;
        $error_message = 'Not authorized';
    }
}
if (!empty($error_code)) {
    $response_data = array(
        'api_status' => 404,
        'errors' => array(
            'error_id' => $error_code,
            'error_text' => $error_message
        )
    );
    $response_data['api_status'] = (int)$response_data['api_status'];
    http_response_code($response_data['api_status']);
    echo json_encode($response_data, JSON_PRETTY_PRINT);
    exit();
}

require_once "$api";
if (!empty($error_code)) {
    $response_data = array(
        'api_status' => 400,
        'errors' => array(
            'error_id' => $error_code,
            'error_text' => $error_message
        )
    );
}
$response_data['api_status'] = (int)$response_data['api_status'];
http_response_code($response_data['api_status']);
echo json_encode($response_data, JSON_PRETTY_PRINT);
exit();
mysqli_close($sqlConnect);
unset($wo);
?>

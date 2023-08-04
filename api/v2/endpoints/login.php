<?php

$response_data   = array(
    'api_status' => 400,
    'message' => 'Oops! Something went wrong. Please try again later',
);
$required_fields = array(
    'username',
);
foreach ($required_fields as $key => $value) {
    if (empty($_POST[$value]) && empty($error_code)) {
        $error_code    = 3;
        $error_message = $value . ' (POST) is missing';
    }
}
if (empty($error_code)) {
    $username       = $_POST['username'];
    $password       = $_POST['password'];
}
<?php

$response_data   = array(
    'api_status' => 400,
    'message' => 'Oops! Something went wrong. Please try again later',
);

if(isset($_POST['slug']) && isset($_POST['title']) && isset($_POST['body'])){
    $slug = Secure($_POST['slug']);
    $title = Secure($_POST['title']);
    $body = Secure($_POST['body']);
    $response_data = array(
        'api_status' => 200,
        'data' => sendFirebaseNotifications($title,$body,$slug)
    );
}
else{
    $response_data   = array(
        'api_status' => 400,
        'message' => 'Missing Parameters',
    );
}


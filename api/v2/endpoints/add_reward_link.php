<?php

$response_data   = array(
    'api_status' => 400,
    'message' => 'Oops! Something went wrong. Please try again later',
);

if(isset($_POST['title']) || isset($_POST['link']) || isset($_POST['slug'])){
    $title = Secure($_POST['title']);
    $link = Secure($_POST['link']);
    $slug = Secure($_POST['slug']);
    $is_redeem = isset($_POST['is_redeem']) ? $_POST['is_redeem'] : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    $sql = "INSERT INTO " .T_REWARD_LINKS. " (`slug`,`title`, `link`, `is_redeem`, `type`) VALUES ('$slug','$title', '$link','$is_redeem', '$type')";
    if (mysqli_query($sqlConnect, $sql)) {
        $notification_response = sendFirebaseNotifications($_POST['notification_title'],$_POST['notification_body'],$slug);
        $response_data   = array(
            'api_status' => 200,
            'message' => 'Data saved successfully',
            'notification_response' => $notification_response,
        );
    } else {
        $error_code    = 500;
        $error_message = 'Error saving data: ' . mysqli_error($sqlConnect);
    }
}
else{
    $response_data   = array(
        'api_status' => 400,
        'message' => 'Missing Parameters',
    );
}

?>

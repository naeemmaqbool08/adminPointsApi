<?php

$response_data   = array(
    'api_status' => 400,
    'message' => 'Oops! Something went wrong. Please try again later',
);

if(isset($_POST['title']) && isset($_POST['link']) && isset($_POST['body'])){
    $title = Secure($_POST['title']);
    $link = Secure($_POST['link']);
    $body = Secure($_POST['body']);

    $sql = "INSERT INTO " .T_LINKS. " (`title`,`link`, `body`) VALUES ('$title', '$link','$body')";
    if (mysqli_query($sqlConnect, $sql)) {
        $response_data   = array(
            'api_status' => 200,
            'message' => 'Data saved successfully',
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

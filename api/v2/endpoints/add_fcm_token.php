<?php

$response_data   = array(
    'api_status' => 400,
    'message' => 'Oops! Something went wrong. Please try again later',
);

if(isset($_POST['token']) && !empty($_POST['token']) && isset($_POST['slug'])){
    $token = $_POST['token'];
    $slug = Secure($_POST['slug']);

    $sql = mysqli_query($sqlConnect,"SELECT * FROM " .T_FCM_TOKENS. " WHERE token = '$token' AND slug = '$slug'",);
    if ($sql) {
        $numRows = mysqli_num_rows($sql);
        if ($numRows > 0) {
            $response_data   = array(
                'api_status' => 200,
                'message' => 'Token already exist',
            );
        }else{
            $sql = "INSERT INTO " .T_FCM_TOKENS. " (`slug`,`token`) VALUES ('$slug','$token')";
            if (mysqli_query($sqlConnect, $sql)) {
                $response_data   = array(
                    'api_status' => 200,
                    'message' => 'Token saved successfully',
                );
            } else {
                $error_code    = 500;
                $error_message = 'Error saving data: ' . mysqli_error($sqlConnect);
            }
        }
    }
}
else{
    $response_data   = array(
        'api_status' => 400,
        'message' => 'Missing Parameters',
    );
}

?>
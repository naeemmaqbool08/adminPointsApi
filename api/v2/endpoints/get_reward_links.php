<?php

$response_data   = array(
    'api_status' => 400,
    'message' => 'Oops! Something went wrong. Please try again later',
);

if(isset($_POST['slug'])){
    $slug = Secure($_POST['slug']);
    $response_data = array(
        'api_status' => 200,
        'data' => getRewardLinks(0,$slug)
    );
}
else{
    $response_data   = array(
        'api_status' => 400,
        'message' => 'Missing Parameters',
    );
}


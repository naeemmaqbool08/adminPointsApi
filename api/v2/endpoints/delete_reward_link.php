<?php

$response_data   = array(
    'api_status' => 200,
    'message' => 'Data delete successfully',
);

if(isset($_POST['id'])){
    $id = Secure($_POST['id']);
    
    $query = "DELETE FROM ". T_REWARD_LINKS ." WHERE id = " . (int)$id;
    $result = mysqli_query($sqlConnect, $query);
    
    if (mysqli_query($sqlConnect, $query)) {
        $response_data   = array(
            'api_status' => 200,
            'message' => 'Data delete successfully',
        );
    } else {
        $error_code    = 500;
        $error_message = 'Error saving data: ' . mysqli_error($sqlConnect);
    }
}
else{
    $error_code    = 400;
    $error_message = 'ID parameter is missing';
}

?>

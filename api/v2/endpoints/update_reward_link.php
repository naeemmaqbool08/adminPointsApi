<?php

$response_data   = array(
    'api_status' => 200,
    'message' => 'Data update successfully',
);

if(isset($_POST['id'])){
    $id = Secure($_POST['id']);
    $title = isset($_POST['title']) ? Secure($_POST['title']) : '';
    $link = isset($_POST['link']) ? Secure($_POST['link']) : '';
    $is_redeem = isset($_POST['is_redeem']) ? Secure($_POST['is_redeem']) : 0;
    $type = isset($_POST['type']) ? Secure($_POST['type']) : '';
    $android_collect = isset($_POST['android_collect']) ? Secure($_POST['android_collect']) : 0;
    
    if(!empty($title) || !empty($link) || !empty($is_redeem) || !empty($type) || !empty($android_collect))
    {
        $data = getRewardLinks($_POST['id']);
        $query = "UPDATE ". T_REWARD_LINKS ." SET ";

        if(count($data) > 0){
            $updateValues = array();
            if (!empty($title)) {
                $updateValues[] = "title = '$title'";
            }
            if (!empty($link)) {
                $updateValues[] = "link = '$link'";
            }
            if(!empty($is_redeem)){
                $updateValues[] = "is_redeem = '$is_redeem'";
            }
            if(!empty($type)){
                $updateValues[] = "type = '$type'";
            }
            if(!empty($android_collect)){
                $updateValues[] = "android_collect = ".(int)$data[0]['android_collect'] + (int)$android_collect;
            }
            $query .= implode(", ", $updateValues);
            $query .= " WHERE id = " . (int)$id;
    
            if (mysqli_query($sqlConnect, $query)) {
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
            $error_code    = 404;
            $error_message = 'Record Not found';
        }
        
    }
    else{
        $error_code    = 404;
        $error_message = 'Invalid or missing parameters';
    }
}
else{
    $error_code    = 400;
    $error_message = 'ID parameter is missing';
}

?>

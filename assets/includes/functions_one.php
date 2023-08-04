<?php 

function ValidateAccessToken($access_token = '') {
    global $wo, $sqlConnect;
    if (empty($access_token)) {
        return false;
    }
    $access_token = Secure($access_token);
    $query        = mysqli_query($sqlConnect, "SELECT user_id FROM " . T_USERS . " WHERE `session_id` = '{$access_token}' LIMIT 1");
    $query_sql    = mysqli_fetch_assoc($query);
    if (isset($query_sql['user_id']) && $query_sql['user_id'] > 0) {
        return $query_sql['user_id'];
    }
    return false;
}

function UserData($user_id, $password = true) {
    global $wo, $sqlConnect, $cache, $db;
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $data           = array();
    $user_id        = Secure($user_id);
    $query_one      = "SELECT * FROM " . T_USERS . " WHERE `user_id` = '{$user_id}'";
    $sql = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($sql)) {
        $fetched_data = mysqli_fetch_assoc($sql);
    }
    if (empty($fetched_data)) {
        return array();
    }
    if ($password == false) {
        unset($fetched_data['password']);
    }

    return $fetched_data;
}

function getRewardLinks($id = 0) {
    global $wo, $sqlConnect, $cache, $db;
    $data           = array();
    $query_one      = "SELECT * FROM " . T_REWARD_LINKS;
    if(!empty($id)){
        $query_one .= " WHERE id = ".$id;
    }
    $sql = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($sql)) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $data[] = $row;
        }
    }
    return $data;
}
function sendFirebaseNotifications($title,$body) {
    global $wo, $sqlConnect, $cache, $db;
    $fcm_tokens           = array();
    $query_one      = "SELECT * FROM " . T_FCM_TOKENS;
    $sql = mysqli_query($sqlConnect, $query_one);
    if (mysqli_num_rows($sql)) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $fcm_tokens[] = $row;
        }
    }
    $title = isset($title) ? Secure($title) : '';
    $body = isset($body) ? Secure($body) : '';
    $url = 'https://fcm.googleapis.com/fcm/send';
    $headers = array(
        'Authorization: key=' . FCM_SERVER_KEY,
        'Content-Type: application/json'
    );
    $notification = array(
        'title' => $title,
        'body' => $body,
    );
    $payload = array(
        'registration_ids' => $fcm_tokens,
        'notification' => $notification,
    );

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
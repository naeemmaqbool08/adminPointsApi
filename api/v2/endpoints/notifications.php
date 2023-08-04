<?php

define('FCM_SERVER_KEY', 'AAAA8lCM1J8:APA91bH2k5EvT5lkpRCysTPtZk-D-6kN-znopA4bEfylZKxLWsY1tD-FoDq14erpvgfAev_LnccaaVWeSJd6r5M3RXg5oxOge-Jw3XquV_83HlEfkgfZYHHsutM_F6sWN-0XgjdxxFJD');

$registrationIds = isset($_POST['registration_ids']) ? Secure($_POST['registration_ids']) : array();
$title = isset($_POST['title']) ? Secure($_POST['title']) : '';
$body = isset($_POST['body']) ? Secure($_POST['body']) : '';
$data = array();

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
  	'to' => '',
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

// Usage example:
// $registrationIds = array('DEVICE_REGISTRATION_TOKEN_1', 'DEVICE_REGISTRATION_TOKEN_2');
// $title = 'Notification Title';
// $body = 'This is the notification body';
// $data = array('key1' => 'value1', 'key2' => 'value2'); // Optional: Add any custom data you want to send along with the notification.
$response_data = array(
    'api_status' => 200,
    'data' => $response
);

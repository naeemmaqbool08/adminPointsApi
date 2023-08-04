<?php

function printt($data){
    echo "<pre>";
    print_r($data);
    exit;
}

function GetHeader($header){
    if (empty($header)) {
        return null;
    }
    $temp = strtoupper(str_replace('-', '_', $header));
    if (isset($_SERVER['HTTP_' . $temp])) {
        return $_SERVER['HTTP_' . $temp];
    }
    if (isset($_SERVER[$temp]) && in_array($temp, array('CONTENT_TYPE', 'CONTENT_LENGTH'))){
        return $_SERVER[$temp];
    }
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers[$header])) {
            return $headers[$header];
        }
        $header = strtolower($header);
        foreach ($headers as $key => $value) {
            if (strtolower($key) == $header) {
                return $value;
            }
        }
    }
    return null;
}

function Secure($string, $censored_words = 1, $br = true, $strip = 0) {
    global $sqlConnect, $mysqlMaria;
    $mysqlMaria->setSQLType($sqlConnect);
    $string = trim($string);
    $string = cleanString($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    if ($br == true) {
        $string = str_replace('\r\n', " <br>", $string);
        $string = str_replace('\n\r', " <br>", $string);
        $string = str_replace('\r', " <br>", $string);
        $string = str_replace('\n', " <br>", $string);
    } else {
        $string = str_replace('\r\n', "", $string);
        $string = str_replace('\n\r', "", $string);
        $string = str_replace('\r', "", $string);
        $string = str_replace('\n', "", $string);
    }
    if ($strip == 1) {
        $string = stripslashes($string);
    }
    $string = str_replace('&amp;#', '&#', $string);
    if ($censored_words == 1) {
        global $config;
        $censored_words = @explode(",", $config['censored_words']);
        foreach ($censored_words as $censored_word) {
            $censored_word = trim($censored_word);
            $string        = str_replace($censored_word, '****', $string);
        }
    }
    return $string;
}

function cleanString($string) {
    return $string = preg_replace("/&#?[a-z0-9]+;/i", "", $string);
}


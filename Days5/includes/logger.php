<?php
function getClientIP() {
    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CF_CONNECTING_IP',
        'REMOTE_ADDR'
    ];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]);
            $ip = trim($ipList[0]);
            // Ép ::1 thành 127.0.0.1 để dễ đọc
            return ($ip === '::1') ? '127.0.0.1' : $ip;
        }
    }

    return 'UNKNOWN';
}

function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser';
}

function writeLog($action, $filename = '') {
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $ip = getClientIP();
    $agent = getUserAgent();
    $logDir = "logs";

    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $logFile = "$logDir/log_$date.txt";
    $message = "[$time] - IP: $ip - Hành động: $action";

    if ($filename) {
        $message .= " - File: $filename";
    }

    $message .= " - Trình duyệt: $agent" . PHP_EOL;

    file_put_contents($logFile, $message, FILE_APPEND);
}

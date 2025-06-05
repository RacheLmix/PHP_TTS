<?php
function writeLog($action, $filename = '') {
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];
    $logDir = "logs";
    if (!file_exists($logDir)) mkdir($logDir);

    $logFile = "$logDir/log_$date.txt";
    $message = "[$time] - $ip - $action";
    if ($filename) {
        $message .= " - File: $filename";
    }
    $message .= PHP_EOL;

    file_put_contents($logFile, $message, FILE_APPEND);
}

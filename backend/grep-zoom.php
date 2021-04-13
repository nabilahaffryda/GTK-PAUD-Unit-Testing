<?php

$url = 'https://us02web.zoom.us/rec/share/Go6mKTh2YUN0vLAb8dS7VOZ-b6AzKX-PC8fz8PZF5m6KFrck0-xDIheEiJ0KoQKy.dezexAgMcbUwYM1L';
$url = 'https://us02web.zoom.us/rec/share/eSiGzH6ee2rNE1qLImCHnSz07VpBHxDZLVuu1W87GnsneJkj_VNZb1Ib4R9_iZyr.j0P1k9r3-cCc-T2G';
// $url  = 'C:\Users\Azhar\Desktop\zoom.html';
$body = file_get_contents($url);

$pattern = '/^<track src="([^"]+)".*$/m';

if (!preg_match_all($pattern, $body, $matches)) {
    echo "No matches found";
    return;
}

// echo "Found matches:\n";
// var_dump($matches[1][0]);

$url = "https://us02web.zoom.us/" . $matches[1][0];
// $body = file_get_contents($url);

// $url = 'C:\Users\Azhar\Desktop\webvtt.txt';
// file_put_contents($url, $body);

$body  = file_get_contents($url);
$lines = explode("\n", $body);
var_dump([
    'body'  => $body,
    'lines' => $lines,
]);


$startTime = false;
$endTime   = false;
foreach ($lines as $line) {
    if (preg_match("/^.*(\d{2}:\d{2}:\d{2}[\d\.]+) --> (\d{2}:\d{2}:\d{2}[\d\.]+).*$/", $line, $match)) {
        $times = explode('-->', $line);
        if (!$startTime) {
            $startTime = trim($times[0]);
        }
        $endTime = trim($times[1]);
    }
}

var_dump([
    $startTime,
    $endTime,
]);

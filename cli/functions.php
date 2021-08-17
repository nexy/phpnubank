<?php
function generate_random_id()
{
    $base = 'abcdefghijklmnopqrstuvwxyz01234567890';
    $id = '';

    while (strlen($id) < 12) {
        $id .= $base[ mt_rand(0, strlen($base)-1) ];
    }

    return $id;
}

function log_message($message)
{
    echo "{$message}\n";
}

function save_cert($cert, $name)
{
    $file = fopen(__DIR__ . "/../cert/" . $name,  "wb");
    fwrite($file, $cert);
    fclose($file);
}

function save_device_id($deviceId)
{
    file_put_contents(__DIR__ . "/../cert/device_id.txt",  $deviceId);
}
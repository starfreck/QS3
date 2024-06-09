<?php
// server_info.php

function getServerInfo($name, $storage_size)
{
    $memory_limit = ini_get('memory_limit');
    $post_max_size = ini_get('post_max_size');
    $upload_max_filesize = ini_get('upload_max_filesize');

    $server_info = array(
        "name" => $name,
        "storage_size" => $storage_size,
        "memory_limit" => $memory_limit,
        "post_max_size" => $post_max_size,
        "upload_max_filesize" => $upload_max_filesize
    );

    return $server_info;
}

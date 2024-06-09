<?php
// mime_type.php

function get_content_type($filename)
{
    $file_info = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $file_info->file($filename);
    return $mime_type;
}

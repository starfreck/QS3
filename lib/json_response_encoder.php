<?php
// json_response_encoder.php

// Helper function to send JSON response
function send_json_response($status_code, $message, $data = [])
{
    http_response_code($status_code);
    header('Content-Type:application/json');
    echo json_encode(array_merge(["message" => $message], $data));
}

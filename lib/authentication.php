<?php
// authentication.php

// Helper function to check API key
function check_api_key($api_key, $provided_key)
{
    if ($provided_key !== $api_key) {
        send_json_response(401, "Unauthorized: Invalid API key.");
        exit;
    }
}

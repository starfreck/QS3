<?php
// index.php
foreach (glob("./lib/*.php") as $filename) {
    require $filename;
}

// Load configuration from .env file
load_env(__DIR__ . '/.env');

// Configuration
$server_name    = $_ENV['NAME'];
$storage_size   = $_ENV['STORAGE_SIZE'];
$target_dir     = $_ENV['TARGET_DIR'];
$max_file_size  = $_ENV['MAX_FILE_SIZE'];
$base_url       = $_ENV['BASE_URL'];
$api_key        = $_ENV['API_KEY'];


// Check API key for POST, DELETE and PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        send_json_response(401, "Unauthorized: No API key provided.");
        exit;
    }
    check_api_key($api_key, $headers['Authorization']);
}

// File Create Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['method']) || $_POST['method'] !== 'delete' || $_POST['method'] !== 'put')) {
    if (isset($_FILES['fileToUpload'])) {
        if ($_FILES["fileToUpload"]["size"] > $max_file_size) {
            send_json_response(413, "File size exceeds maximum limit.");
            exit;
        }

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $moved = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        if ($moved) {
            $file_url = $base_url . "?" . http_build_query(["filename" => basename($target_file)]);
            send_json_response(200, "The file has been uploaded.", ["url" => $file_url]);
        } else {
            send_json_response(500, "Sorry, there was an error uploading your file.");
        }
    } else {
        send_json_response(400, "No file uploaded.");
    }
}

// File Delete Endpoint
if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'delete') || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $filename = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST['filename'] : $_GET['filename'];
    if (isset($filename)) {
        $file_path = $target_dir . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
            send_json_response(200, "File deleted successfully.");
        } else {
            send_json_response(404, "File not found.");
        }
    } else {
        send_json_response(400, "No filename provided.");
    }
}

// File Download Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['filename'])) {
        $filename = $_GET['filename'];
        $file_path = $target_dir . $filename;

        if (file_exists($file_path)) {
            $content_type = get_content_type($file_path);
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $content_type);
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            flush(); // Flush system output buffer
            readfile($file_path);
            exit;
        } else {
            send_json_response(404, "File not found.");
        }
    } else {
        $server_info = getServerInfo($server_name, $storage_size);
        send_json_response(200, "No filename provided.", $server_info);
    }
}

// TODO: Please implement PUT method here if required
// if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'put') || $_SERVER['REQUEST_METHOD'] === 'PUT') {
//     send_json_response(400, "Please implement PUT method!");
// }

// TODO: Please implement PATCH method here if required
// if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'patch') || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
//     send_json_response(400, "Please implement PATCH method!");
// }
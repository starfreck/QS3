<?php
// env_loader.php
function load_env($env_file_path)
{
    if (file_exists($env_file_path)) {
        $env_lines = file($env_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($env_lines as $line) {
            if (str_contains($line, '=')) {
                list($name, $value) = explode('=', $line, 2);
                $_ENV[$name] = $value;
            }
        }
    }
}

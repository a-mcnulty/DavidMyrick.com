<?php
// Router script for PHP built-in web server
// This mimics the .htaccess rewrite rules for local development only

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

// Serve static files directly
if (preg_match('/\.(css|js|jpg|jpeg|gif|png|php|html|pdf|mp4|woff|woff2|ico|svg)$/', $path)) {
    return false; // Serve the file as-is
}

// Skip proshop directory
if (strpos($path, '/proshop') === 0) {
    return false;
}

// Check if it's a real file or directory
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
    return false;
}

// Homepage
if ($path === '/' || $path === '') {
    require 'index.php';
    return true;
}

// Route everything else through route.php
$_SERVER['QUERY_STRING'] = ltrim($path, '/');
if ($query) {
    $_SERVER['QUERY_STRING'] .= '?' . $query;
}

require 'route.php';
return true;

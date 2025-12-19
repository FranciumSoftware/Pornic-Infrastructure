<?php
// filepath: c:\xampp\htdocs\Pornic\admin\edit\upload_image.php
session_start();
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    exit;
}

$uploadDir = '../../Ressources/news/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if (isset($_FILES['file'])) {
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = 'news_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $targetFile = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $url = '../../Ressources/news/' . $fileName;
        // TinyMCE attend une URL relative à la racine du site
        echo json_encode(['location' => $url]);
        exit;
    }
}
http_response_code(400);
echo json_encode(['error' => 'Upload failed']);
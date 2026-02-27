<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function handleImageUpload(string $field, string $relativeDir, array $allowedExt = ['jpg', 'jpeg', 'png', 'webp']): ?string
{
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Ошибка загрузки файла.');
    }

    $tmpPath = $file['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = (string) finfo_file($finfo, $tmpPath);
    finfo_close($finfo);

    $mimeToExt = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($mimeToExt[$mime])) {
        throw new RuntimeException('Разрешены только изображения JPG, PNG, WEBP.');
    }

    $ext = $mimeToExt[$mime];
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Недопустимый формат файла.');
    }

    $uploadDir = __DIR__ . '/../public/' . trim($relativeDir, '/');
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Не удалось создать папку для загрузок.');
    }

    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmpPath, $destination)) {
        throw new RuntimeException('Не удалось сохранить файл.');
    }

    return url('/' . trim($relativeDir, '/') . '/' . $filename);
}

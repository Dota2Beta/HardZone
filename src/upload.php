<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function handleImageUpload(
    string $field,
    string $relativeDir,
    array $allowedExt = ['jpg', 'jpeg', 'png', 'webp'],
    ?array $cropOptions = null
): ?string {
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

    if ($cropOptions !== null) {
        $tmpPath = processAvatarCrop($tmpPath, $mime, $cropOptions);
    }

    $uploadDir = __DIR__ . '/../public/' . trim($relativeDir, '/');
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Не удалось создать папку для загрузок.');
    }

    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmpPath, $destination) && !rename($tmpPath, $destination)) {
        throw new RuntimeException('Не удалось сохранить файл.');
    }

    return url('/' . trim($relativeDir, '/') . '/' . $filename);
}

function processAvatarCrop(string $tmpPath, string $mime, array $crop): string
{
    if (!extension_loaded('gd')) {
        return $tmpPath;
    }

    $zoom = max(1.0, min(3.0, (float) ($crop['zoom'] ?? 1.0)));
    $offsetXPercent = max(-100.0, min(100.0, (float) ($crop['x'] ?? 0.0)));
    $offsetYPercent = max(-100.0, min(100.0, (float) ($crop['y'] ?? 0.0)));
    $canvas = max(120, min(600, (int) ($crop['canvas'] ?? 260)));

    $source = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($tmpPath),
        'image/png' => @imagecreatefrompng($tmpPath),
        'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmpPath) : false,
        default => false,
    };

    if (!$source) {
        return $tmpPath;
    }

    $iw = imagesx($source);
    $ih = imagesy($source);

    $baseScale = max($canvas / $iw, $canvas / $ih);
    $displayScale = $baseScale * $zoom;
    $dw = $iw * $displayScale;
    $dh = $ih * $displayScale;

    $maxOffsetX = max(0.0, ($dw - $canvas) / 2.0);
    $maxOffsetY = max(0.0, ($dh - $canvas) / 2.0);

    $offsetX = $maxOffsetX * ($offsetXPercent / 100.0);
    $offsetY = $maxOffsetY * ($offsetYPercent / 100.0);

    $dx = (($canvas - $dw) / 2.0) + $offsetX;
    $dy = (($canvas - $dh) / 2.0) + $offsetY;

    $srcX = (int) max(0, round(-$dx / $displayScale));
    $srcY = (int) max(0, round(-$dy / $displayScale));
    $srcW = (int) min($iw - $srcX, round($canvas / $displayScale));
    $srcH = (int) min($ih - $srcY, round($canvas / $displayScale));

    $targetSize = 320;
    $target = imagecreatetruecolor($targetSize, $targetSize);

    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagealphablending($target, false);
        imagesavealpha($target, true);
        $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
        imagefilledrectangle($target, 0, 0, $targetSize, $targetSize, $transparent);
    }

    imagecopyresampled($target, $source, 0, 0, $srcX, $srcY, $targetSize, $targetSize, max(1, $srcW), max(1, $srcH));

    $croppedPath = tempnam(sys_get_temp_dir(), 'hz_av_');
    if (!$croppedPath) {
        imagedestroy($source);
        imagedestroy($target);

        return $tmpPath;
    }

    $saved = match ($mime) {
        'image/jpeg' => imagejpeg($target, $croppedPath, 90),
        'image/png' => imagepng($target, $croppedPath, 7),
        'image/webp' => function_exists('imagewebp') ? imagewebp($target, $croppedPath, 90) : false,
        default => false,
    };

    imagedestroy($source);
    imagedestroy($target);

    if (!$saved) {
        @unlink($croppedPath);

        return $tmpPath;
    }

    return $croppedPath;
}


function saveCroppedAvatarFromDataUrl(string $dataUrl, string $relativeDir): ?string
{
    if ($dataUrl === '' || !str_starts_with($dataUrl, 'data:image/')) {
        return null;
    }

    if (!preg_match('#^data:image/(png|jpeg|webp);base64,(.+)$#', $dataUrl, $m)) {
        return null;
    }

    $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
    $binary = base64_decode($m[2], true);
    if ($binary === false) {
        return null;
    }

    $uploadDir = __DIR__ . '/../public/' . trim($relativeDir, '/');
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Не удалось создать папку для аватара.');
    }

    $filename = bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = $uploadDir . '/' . $filename;
    if (file_put_contents($destination, $binary) === false) {
        throw new RuntimeException('Не удалось сохранить отредактированный аватар.');
    }

    return url('/' . trim($relativeDir, '/') . '/' . $filename);
}

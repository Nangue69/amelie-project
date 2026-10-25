<?php

declare(strict_types=1);

namespace App\Core;

final class Uploader
{
    private const MAX_BYTES = 6291456; // 6 MB

    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
    ];

    /**
     * Guarda una imagen subida y la registra en la biblioteca de medios.
     * Devuelve ['ok'=>bool,'path'=>string,'message'=>string].
     */
    public static function image(array $file, string $alt = ''): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return self::fail('No se ha podido subir el archivo.');
        }

        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            return self::fail('La imagen supera el tamaño máximo de 6 MB.');
        }

        $tmp = (string) ($file['tmp_name'] ?? '');

        if (!is_uploaded_file($tmp)) {
            return self::fail('Archivo no válido.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = (string) $finfo->file($tmp);

        if (!isset(self::ALLOWED[$mime])) {
            return self::fail('Formato no permitido. Usa JPG, PNG, WebP o AVIF.');
        }

        $dimensions = @getimagesize($tmp);

        if ($dimensions === false) {
            return self::fail('El archivo no es una imagen válida.');
        }

        $dir = PUBLIC_PATH . '/uploads/' . date('Y/m');

        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            return self::fail('No se ha podido crear la carpeta de destino.');
        }

        $base      = slugify(pathinfo((string) ($file['name'] ?? 'imagen'), PATHINFO_FILENAME));
        $filename  = $base . '-' . bin2hex(random_bytes(4)) . '.' . self::ALLOWED[$mime];
        $target    = $dir . '/' . $filename;

        if (!move_uploaded_file($tmp, $target)) {
            return self::fail('No se ha podido guardar la imagen.');
        }

        @chmod($target, 0644);

        $path = '/uploads/' . date('Y/m') . '/' . $filename;

        Database::insert('media', [
            'filename' => $filename,
            'path'     => $path,
            'mime'     => $mime,
            'size'     => (int) ($file['size'] ?? 0),
            'width'    => (int) $dimensions[0],
            'height'   => (int) $dimensions[1],
            'alt'      => mb_substr($alt, 0, 255),
        ]);

        return ['ok' => true, 'path' => $path, 'message' => 'Imagen subida.'];
    }

    public static function delete(int $mediaId): bool
    {
        $media = Database::one('SELECT * FROM `media` WHERE `id` = :id', ['id' => $mediaId]);

        if ($media === null) {
            return false;
        }

        $file = PUBLIC_PATH . $media['path'];

        if (is_file($file) && str_starts_with(realpath($file) ?: '', realpath(PUBLIC_PATH . '/uploads') ?: '')) {
            @unlink($file);
        }

        Database::delete('media', '`id` = :id', ['id' => $mediaId]);

        return true;
    }

    private static function fail(string $message): array
    {
        return ['ok' => false, 'path' => '', 'message' => $message];
    }
}

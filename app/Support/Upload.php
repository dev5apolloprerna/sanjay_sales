<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Saves uploaded images directly into the website's uploads folder
 * (plain PHP file handling – Laravel's "storage" folder is NOT used).
 *
 * Folder = public_path('uploads')  (recommended – leave UPLOAD_PATH empty)
 *   - local:  <project>/public/uploads
 *   - live:   public_html/sanjay_sales/uploads (index.php calls usePublicPath(__DIR__))
 * Or force a folder with UPLOAD_PATH in .env:
 *     UPLOAD_PATH=/home1/getdemo/public_html/sanjay_sales/uploads   (absolute)
 *     UPLOAD_PATH=../public_html/sanjay_sales/uploads               (relative to base_path)
 *
 * The database stores the path inside that folder, e.g. "products/abc.jpg",
 * and the image URL becomes  <site>/uploads/products/abc.jpg
 */
class Upload
{
    /** Absolute path of the uploads folder. */
    public static function dir(): string
    {
        $path = trim((string) config('app.upload_path'));

        // Default: <public folder>/uploads. On the live server index.php sets the
        // public folder to public_html/sanjay_sales, so this is the right place.
        if ($path === '') {
            return public_path('uploads');
        }

        $isAbsolute = str_starts_with($path, '/') || preg_match('~^[A-Za-z]:[\\\\/]~', $path);
        // forgive a missing leading "/" (e.g. "home1/getdemo/public_html/...")
        if (! $isAbsolute && ! str_starts_with($path, '.') && is_dir(dirname('/'.$path))) {
            $path = '/'.$path;
            $isAbsolute = true;
        }

        // relative paths are relative to the Laravel project folder (base_path)
        return rtrim($isAbsolute ? $path : base_path($path), '/\\');
    }

    /** Saves the file into uploads/<folder>/ and returns "folder/filename.ext". */
    public static function store(?UploadedFile $file, string $folder): ?string
    {
        if (! $file) {
            return null;
        }
        $folder = trim($folder, '/');
        $target = self::dir().DIRECTORY_SEPARATOR.$folder;

        if (! is_dir($target) && ! @mkdir($target, 0755, true) && ! is_dir($target)) {
            throw new RuntimeException("Cannot create the upload folder: {$target}. Check that it exists and is writable (permission 755/775).");
        }
        if (! is_writable($target)) {
            throw new RuntimeException("The upload folder is not writable: {$target}. Set its permission to 755 or 775.");
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $name = Str::random(32).'.'.$ext;
        $file->move($target, $name);

        return $folder.'/'.$name;
    }

    /** Deletes a previously stored file (ignores missing files). */
    public static function delete(?string $path): void
    {
        if (! $path || str_contains($path, '..')) {
            return;
        }
        $full = self::dir().DIRECTORY_SEPARATOR.ltrim($path, '/');
        if (is_file($full)) {
            @unlink($full);
        }
    }

    /**
     * Returns the new path for a model attribute:
     * - a new file was uploaded  -> store it and delete the old one
     * - the "remove" box was set  -> delete the old one, return null
     * - otherwise                 -> keep the old one
     */
    public static function replace(?UploadedFile $file, ?string $old, string $folder, bool $remove = false): ?string
    {
        if ($file) {
            $new = self::store($file, $folder);
            self::delete($old);

            return $new;
        }
        if ($remove) {
            self::delete($old);

            return null;
        }

        return $old;
    }
}

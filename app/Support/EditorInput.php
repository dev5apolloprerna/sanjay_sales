<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * CKEditor fields are sent base64-encoded ("__b64__:...") by components/editor-assets.blade.php,
 * because the hosting server's ModSecurity firewall blocks POST requests that contain raw HTML
 * ("Not Acceptable!"). This turns them back into HTML before validation.
 */
class EditorInput
{
    public const PREFIX = '__b64__:';

    public static function decode(Request $request): void
    {
        foreach ($request->input() as $key => $value) {
            $decoded = self::walk($value);
            if ($decoded !== $value) {
                $request->merge([$key => $decoded]);
            }
        }
    }

    private static function walk(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map([self::class, 'walk'], $value);
        }
        if (is_string($value) && str_starts_with($value, self::PREFIX)) {
            $html = base64_decode(substr($value, strlen(self::PREFIX)), true);

            return $html === false ? null : $html;
        }

        return $value;
    }
}

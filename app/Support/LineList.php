<?php

namespace App\Support;

/**
 * Converts the admin's plain textareas to/from the JSON stored on products.
 *
 *   list():  one item per line                      -> ["a", "b"]
 *   specs(): "Group | Label | Value" (or "Label | Value", group = General)
 *            -> [{"group":"General","items":[{"label":"..","value":".."}]}]
 *   faqs():  "Question | Answer" one per line        -> [{"question":"..","answer":".."}]
 */
class LineList
{
    private static function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $text)), 'strlen'));
    }

    public static function list(?string $text): ?array
    {
        return self::lines($text) ?: null;
    }

    public static function specs(?string $text): ?array
    {
        $groups = [];
        foreach (self::lines($text) as $line) {
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) >= 3) {
                [$group, $label] = $parts;
                $value = implode(' | ', array_slice($parts, 2));
            } elseif (count($parts) === 2) {
                [$group, $label, $value] = ['General', $parts[0], $parts[1]];
            } else {
                continue;
            }
            $groups[$group ?: 'General'][] = ['label' => $label, 'value' => $value];
        }

        return $groups ? array_map(fn ($g, $items) => ['group' => $g, 'items' => $items], array_keys($groups), $groups) : null;
    }

    public static function faqs(?string $text): ?array
    {
        $out = [];
        foreach (self::lines($text) as $line) {
            $parts = array_map('trim', explode('|', $line, 2));
            if (count($parts) === 2 && $parts[0] !== '' && $parts[1] !== '') {
                $out[] = ['question' => $parts[0], 'answer' => $parts[1]];
            }
        }

        return $out ?: null;
    }

    // ---- back to textarea text (edit form) ----

    public static function listText(?array $items): string
    {
        return implode("\n", $items ?? []);
    }

    public static function specsText(?array $groups): string
    {
        $lines = [];
        foreach ($groups ?? [] as $g) {
            foreach ($g['items'] ?? [] as $i) {
                $lines[] = ($g['group'] ?? 'General').' | '.($i['label'] ?? '').' | '.($i['value'] ?? '');
            }
        }

        return implode("\n", $lines);
    }

    public static function faqsText(?array $faqs): string
    {
        return implode("\n", array_map(fn ($f) => ($f['question'] ?? '').' | '.($f['answer'] ?? ''), $faqs ?? []));
    }
}

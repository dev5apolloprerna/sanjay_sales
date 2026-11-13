<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Cleans HTML coming from the CKEditor fields before it is saved / sent to the website.
 * Keeps formatting tags (bold, italic, lists, headings, links, tables), drops scripts,
 * inline styles and event attributes. Returns null when the editor was left empty.
 */
class Html
{
    /** tag => allowed attributes */
    private const ALLOWED = [
        'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'sub' => [], 'sup' => [], 'h2' => [], 'h3' => [], 'h4' => [], 'h5' => [], 'h6' => [], 'blockquote' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'hr' => [],
        'a' => ['href', 'target', 'rel'],
        'figure' => ['class'], 'figcaption' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tfoot' => [], 'tr' => [], 'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
    ];

    /** removed together with their content */
    private const DROP = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'select', 'textarea', 'link', 'meta', 'svg', 'math', 'noscript', 'template'];

    public static function clean(?string $html): ?string
    {
        if (self::isBlank($html)) {
            return null;
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="__root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $doc->getElementById('__root') ?? $doc->getElementsByTagName('div')->item(0);
        if (! $root) {
            return null;
        }
        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        $out = trim($out);

        return self::isBlank($out) ? null : $out;
    }

    /** True when the editor holds nothing visible ("<p><br></p>", "<p>&nbsp;</p>" ...). */
    public static function isBlank(?string $html): bool
    {
        return $html === null || (self::text($html) === null && ! preg_match('/<(table|hr)\b/i', $html));
    }

    /** Plain text version (for listing cards, meta tags, search). */
    public static function text(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }
        $text = html_entity_decode(strip_tags(preg_replace('/<\/(p|li|h\d|tr|blockquote)>|<br\s*\/?>/i', ' ', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));

        return $text === '' ? null : $text;
    }

    private static function walk(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
                continue;
            }
            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);
            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);
                continue;
            }

            self::walk($child);

            if (! array_key_exists($tag, self::ALLOWED)) {
                // unknown wrapper (div, span, font ...): keep its content, drop the tag
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attr) {
                $name = strtolower($attr->name);
                if (! in_array($name, self::ALLOWED[$tag], true)) {
                    $child->removeAttribute($attr->name);
                }
            }

            if ($tag === 'a') {
                $href = trim($child->getAttribute('href'));
                if ($href !== '' && ! preg_match('~^(https?://|mailto:|tel:|/|#)~i', $href)) {
                    $child->removeAttribute('href');
                }
                if ($child->getAttribute('target') === '_blank') {
                    $child->setAttribute('rel', 'noopener noreferrer');
                }
            }
        }
    }
}

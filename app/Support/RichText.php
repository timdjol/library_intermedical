<?php

namespace App\Support;

class RichText
{
    public static function clean(?string $html): string
    {
        $html = strip_tags(
            (string) $html,
            '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><blockquote><a><table><thead><tbody><tr><th><td>'
        );

        $html = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s+(href|src)\s*=\s*("|\')\s*(javascript|data):[^"\']*\2/i', ' $1=$2#$2', $html) ?? '';

        return $html;
    }
}

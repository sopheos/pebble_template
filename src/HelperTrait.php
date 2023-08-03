<?php

namespace Pebble\Template;

trait HelperTrait
{
    private static array $autoclose = [
        "area", "base", "br", "col", "embed", "hr", "img", "input",
        "keygen", "link", "meta", "param", "source", "track", "wbr",
    ];

    /**
     * Builds meta tags
     *
     * @param array $meta
     * @return string
     */
    public static function meta(array $meta): string
    {
        $html = "";
        foreach ($meta as $arg) {
            $tag = isset($arg["rel"]) ? "link" : "meta";
            $html .= self::tag($tag, "", $arg);
        }
        return $html;
    }

    /**
     * Builds a script constant wrapped inside <script> tag
     *
     * @param string $name
     * @param mixed $value The value is json encoded
     * @return string
     */
    public static function jsVars(string $name, $value): string
    {
        $value = json_encode($value, JSON_HEX_APOS);

        return self::tag("script", "const {$name} = {$value};");
    }

    /**
     * Builds scripts tag
     *
     * @param array $urls
     * @return string
     */
    public static function scripts(array $urls, bool $module = false): string
    {
        $html = "";
        $attr = $module ? ["type" => 'module'] : [];
        foreach ($urls as $url) {
            $html .= self::tag("script", "", $attr + [
                'src' => $url
            ]);
        }
        return $html;
    }

    /**
     * Builds styles tag
     *
     * @param array $urls
     * @return string
     */
    public static function styles(array $urls): string
    {
        $html = "";
        foreach ($urls as $url) {
            $html .= self::tag("link", "", [
                "type" => "text/css",
                "rel" => "stylesheet",
                "href" => $url,
            ]);
        }
        return $html;
    }

    /**
     * Builds html tag
     *
     * @param array $urls
     * @return string
     */
    public static function tag(string $tag, string $content = "", array $args = []): string
    {
        $argStr = self::attrToString($args);

        if (in_array($tag, self::$autoclose)) {
            return "<{$tag}{$argStr} />";
        }

        return "<{$tag}{$argStr}>{$content}</{$tag}>\n";
    }

    /**
     * Builds attributes for html tag
     *
     * @param array $attributes
     * @return string
     */
    public static function attrToString($attributes): string
    {
        $html = "";

        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " " . $name;
                }
            } else {
                $value = self::xss((string) $value);
                $html .= " {$name}=\"{$value}\"";
            }
        }

        return $html;
    }

    /**
     * Escapes a value
     *
     * @param mixed $value
     */
    public static function xss(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        if (is_string($value)) {
            return self::htmlEncode($value);
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return self::htmlEncode(json_encode($value));
    }

    private static function htmlEncode(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }
}

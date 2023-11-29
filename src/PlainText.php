<?php

namespace Pebble\Template;

class PlainText
{
    private static ?PlainText $instance = null;

    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public static function add(string $text, mixed ...$items): static
    {
        $items ? vprintf($text, $items) : print($text);
        return self::getInstance();
    }

    public static function eol(int $nb = 1): static
    {
        return self::repeat("\n", $nb);
    }

    public static function tab(int $nb = 1): static
    {
        return self::repeat("\t", $nb);
    }

    public static function space(int $nb = 1): static
    {
        return self::repeat(" ", $nb);
    }

    public static function repeat(string $str, int $nb = 1): static
    {
        return self::add($nb > 1 ? str_repeat($str, $nb) : ($nb === 1 ? $str : ''));
    }
}

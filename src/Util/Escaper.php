<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Util;

final class Escaper
{
    public static function escapeAttributeValue(string $string): string
    {
        return htmlspecialchars(
            $string,
            ENT_COMPAT | ENT_HTML5,
            'UTF-8',
            true
        );
    }

    public static function escapeTextNodeValue(string $string): string
    {
        return htmlspecialchars(
            $string,
            ENT_NOQUOTES | ENT_HTML5,
            'UTF-8',
            true
        );
    }
}
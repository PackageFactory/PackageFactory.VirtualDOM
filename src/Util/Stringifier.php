<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Util;

final class Stringifier
{
    public static function stringify($value): string
    {

    }

    public static function stringifyArray(array $value): string
    {
        if (is_array($value) || is_object($value)) {
            $concatenatedValue = [];


            foreach ($value as $key => $segment) {
                if (is_numeric($key) && is_string($segment)) {
                    $concatenatedValue[] = $segment;
                } elseif (is_string($key) && $segment) {
                    $concatenatedValue[] = $key;
                }
            }

            $this->value = trim(implode(' ', $concatenatedValue));
        } else {
            $this->value = $value;
        }
    }

    public static function stringifyObject($object): string
    {

    }
}
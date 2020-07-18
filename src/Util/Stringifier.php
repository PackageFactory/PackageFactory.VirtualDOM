<?php declare(strict_types=1);
namespace PackageFactory\VirtualDOM\Util;

final class Stringifier
{
    /**
     * @param mixed $value
     * @return string
     */
    public static function stringify($value): string
    {
        if (is_string($value)) {
            return $value;
        } elseif (is_numeric($value)) {
            return (string) $value;
        } elseif (is_bool($value)) {
            return self::stringifyBoolean($value);
        } elseif (is_array($value)) {
            return self::stringifyArray($value);
        } elseif (is_object($value)) {
            return self::stringifyObject($value);
        } else {
            throw new \InvalidArgumentException(
                sprintf('Cannot stringify value of type "%s".', gettype($value))
            );
        }
    }

    /**
     * @param bool $value
     * @return string
     */
    public static function stringifyBoolean(bool $value): string
    {
        return $value ? 'true' : 'false';
    }

    /**
     * @param array $value
     * @return string
     */
    public static function stringifyArray(array $value): string
    {
        $result = [];

        foreach ($value as $key => $segment) {
            if (is_numeric($key) && is_string($segment)) {
                $result[] = $segment;
            } elseif (is_string($key) && $segment) {
                $result[] = $key;
            }
        }

        return trim(implode(' ', $result));
    }

    /**
     * @param mixed $object
     * @return string
     */
    public static function stringifyObject($object): string
    {
        // @TODO: stringify object
        return '[object]';
    }
}
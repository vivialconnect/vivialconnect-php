<?php

namespace VivialConnect\Common;


class Utility
{
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    public static function getClassName($name) {
        if (stripos ($name, '\\')) {
            $classItems = explode ('\\', $name);
            return end ($classItems);
        }
        return $name;
    }

   /**
     * Removes root key from object and returns a new object or array without root key.
     *
     * @param object $data
     *
     * @return array|object
     */
    public static function removeRoot($data) {
        if (is_object($data)) {
            $attributes = get_object_vars($data);
            if (count($attributes) == 1)
                return array_pop($attributes);
        }
        return $data;
    }
}

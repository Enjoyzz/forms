<?php

declare(strict_types=1);

namespace Enjoys\Forms;

final class Helper
{
    public static function arrayRecursiveSearchKeyMap(mixed $needle, array $haystack): array|null
    {
        foreach ($haystack as $firsLevelKey => $value) {
            if ($needle === $value) {
                return array($firsLevelKey);
            } elseif (is_array($value)) {
                $callback = self::arrayRecursiveSearchKeyMap($needle, $value);
                if ($callback) {
                    return array_merge(array($firsLevelKey), $callback);
                }
            }
        }
        return null;
    }
}

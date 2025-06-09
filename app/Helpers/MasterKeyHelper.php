<?php

namespace App\Helpers;

class MasterKeyHelper
{
    public static function interleave(string $a, string $b): string
    {
        $maxLength = max(strlen($a), strlen($b));
        $merged = '';

        for ($i = 0; $i < $maxLength; $i++) {
            if ($i < strlen($a)) $merged .= $a[$i];
            if ($i < strlen($b)) $merged .= $b[$i];
        }

        return $merged;
    }
}

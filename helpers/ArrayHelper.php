<?php 

namespace tifia\helpers;

class ArrayHelper {

    public static function arrayDepth(array $array) : int {
        $max_depth = 1;
    
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::arrayDepth($value) + 1;
    
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
    
        return $max_depth;
    }
} 
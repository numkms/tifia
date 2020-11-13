<?php 

namespace tifia\helpers;

use yii\base\Model;

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

    static function arrayToTree($flatStructure, $pidKey, $idKey) : ArrayTree { 
        $parents = [];
        $depth = 0;
        foreach ($flatStructure as $item){
            $parents[$item[$pidKey]][] = $item;
        }
    
        $fnBuilder = function($items, $parents, $idKey) use (&$fnBuilder, &$depth) {
            
            foreach ($items as $position => $item) {
                $id = $item[$idKey];
                if(isset($parents[$id])) { 
                    $item['children'] = $fnBuilder($parents[$id], $parents, $idKey);
                }
                $items[$position] = $item;
            }
    
            return $items;
        };
        
        $tree = $fnBuilder($parents[array_keys($parents)[0]], $parents, $idKey);

        return new ArrayTree([
            'tree' => $tree,
            'depth' => $depth
        ]);
    }
} 

class ArrayTree extends Model {
    public $tree;
    public $depth;
}
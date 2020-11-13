<?php

namespace tifia\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use tifia\services\ReferalStatsCounter;
use yii\console\widgets\Table;

/**
 * Class IndexController
 */
class IndexController extends Controller {
    
    public function actionIndex(int $userId, string $dateFrom, string $dateTo) {
        $start = microtime(true);
        
        /** @var ReferalStatsCounter $counter*/
        $counter = \Yii::$app->referalStatsCounter;
        $result = $counter->count($userId, $dateFrom, $dateTo);

        echo "Дерево рефералов" . PHP_EOL;
        echo $counter->treeFormatted;
        echo "Таблица результата" . PHP_EOL;
        
        $end = microtime(true);

        $table = new Table();
        echo $table
        ->setHeaders(["Таблица результатов"])
        ->setRows([
                ["Cуммарный объем", $result->summaryVolume],
                ["Прибыльность", $result->summaryProfit],
                ["Прямые рефералы", $result->directReferalsCount],
                ["Все рефералы", $result->allReferalWebCount],
                ["Количество уровней реферальной сетки", $result->referalWebDepth],
                ["Время обработки", ($end - $start) . " секунд"],
        ])
        ->run();
    }
}

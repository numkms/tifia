<?php

namespace tifia\services;

use yii\base\Component;
use tifia\models\Accounts;
use tifia\models\Trades;
use tifia\models\Users;
use \tifia\helpers\ArrayHelper;

class ReferalStatsCounter extends Component {

    protected $userId = null;
    protected $tree = [];
    protected $volumeAndProfitReferalWebSum = [];
    protected $logins = [];
    
    public function count($userId, string $fromDate, string $toDate) : ReferalStatsCounterResult {
        $this->userId = $userId;
        $this->tree = $this->loadTreeByUserId($userId);
        $this->loadTreeVolumeAndProfitReferalWebSums($fromDate, $toDate);

        return new ReferalStatsCounterResult([
            'summaryVolume' => $this->volumeAndProfitReferalWebSum["summaryVolume"],
            'summaryProfit' => $this->volumeAndProfitReferalWebSum["summaryProfit"],
            'directReferalsCount' => $this->treeDirectReferalsCount,
            'allReferalWebCount' => $this->treeAllReferalsCount,
            'referalWebDepth' => $this->referalsWebDepth
        ]);
    }

    protected function formatTreePart($part, $depth): string {
        $result = "";

        foreach ($part as $key => $value) {
            $result .= str_repeat("--", $depth) . $key . PHP_EOL;
            
            if($value) {
                $result .= $this->formatTreePart($value, $depth++);
            }
        }

        return $result;
    }

    public function getTreeFormatted(): string {
        if($this->tree) {
            return "--" . $this->userId . PHP_EOL . $this->formatTreePart($this->tree, 2, "");
        } else {
            throw new yii\base\ErrorException;("Please, run method count fristly");
        }
    }

    protected function loadTreeByUserId($userId): array {
        $childs = [];

        $users = Users::find()->andWhere(['partner_id' => $userId])->with('account')->asArray()->all();

        foreach ($users as $childClient) {
            $this->logins[] = $childClient['account']['login'];
            $childs[$childClient['client_uid']] = $this->loadTreeByUserId($childClient['client_uid']);  
        }   

        return $childs;
    }

    protected function loadTreeVolumeAndProfitReferalWebSums($fromDate, $toDate) {
        
        if ($this->volumeAndProfitReferalWebSum) {
            return;
        }

        $this->volumeAndProfitReferalWebSum =  Trades::find()
        ->select([
            "SUM(volume * coeff_h * coeff_cr) as summaryVolume",
            "SUM(profit) as summaryProfit"
        ])
        ->andWhere([
            'AND',
            ['login' => $this->logins],
            ['between', 'close_time', $fromDate, $toDate]
        ])
        ->asArray()
        ->one();
    }

    protected function getTreeDirectReferalsCount(): int {
        return count($this->tree);
    }

    protected function getTreeAllReferalsCount(): int {
        return count($this->tree, COUNT_RECURSIVE);
    }

    protected function getReferalsWebDepth(): int {
        return ArrayHelper::arrayDepth($this->tree);
    }
}

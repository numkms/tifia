<?php

namespace tifia\services;

use yii\base\Component;
use tifia\models\Accounts;
use tifia\models\Trades;
use tifia\models\Users;
use \tifia\helpers\ArrayHelper;
use yii\helpers\ArrayHelper as BaseArrayHelper;

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

    protected function getUserData($userId): array {
        $users = Users::find()->andWhere(['partner_id' => $userId])->with('account')->asArray()->all();
        
        if(!$users) return [];

        $this->logins = BaseArrayHelper::merge($this->logins, BaseArrayHelper::getColumn($users, 'account.login'));

        return BaseArrayHelper::merge($users, $this->getUSerData(BaseArrayHelper::getColumn($users, 'client_uid')));   
    }
    
    protected function loadTreeByUserId($userId): array {
        return ArrayHelper::arrayToTree($this->getUserData($userId), 'partner_id', 'client_uid');
    }

    protected function loadTreeVolumeAndProfitReferalWebSums($fromDate, $toDate) {
        
        if ($this->volumeAndProfitReferalWebSum) {
            return;
        }

        $this->volumeAndProfitReferalWebSum = Trades::find()
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

    public function getTreeAllReferalsCount(): int {
        return count($this->logins);
    }

    public function getTreeDirectReferalsCount() {
        return count($this->tree);
    }

    public function getReferalsWebDepth() {
        return ArrayHelper::arrayDepth($this->tree);
    }

    public function getTreeFormatted(): string {
        if($this->tree) {
            return "--" . $this->userId . PHP_EOL . $this->formatTreePart($this->tree, 2, "");
        } 
        
        throw new yii\base\ErrorException;("Please, run method count fristly");
    }

    protected function formatTreePart($part, $depth): string {
        $result = "";
        
        foreach ($part as $key => $value) {
            $result .= str_repeat("--", $depth) . $value['client_uid'] . PHP_EOL;            
            if(isset($value['children'])) {
                $result .= $this->formatTreePart($value['children'], $depth++);
            }
        }

        return $result;
    }
    
}

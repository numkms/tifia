<?php

namespace tifia\services;

use yii\base\Model;

class ReferalStatsCounterResult extends Model {
    public $summaryVolume;
    public $summaryProfit;
    public $directReferalsCount;
    public $allReferalWebCount;
    public $referalWebDepth;
}
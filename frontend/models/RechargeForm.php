<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/9
 * Time: 下午4:49
 */

namespace frontend\models;


use common\models\records\RechargeLog;
use yii\base\Model;

class RechargeForm extends Model
{
    const RECHARGE_BASIC = 10000;

    public $amount;

    public function rules()
    {
        return [
            ['amount', 'integer', 'min' => 1, 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => '报单金额'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $model = new RechargeLog();
        $model->user_id = \Yii::$app->user->getId();
        $model->amount = $this->amount * static::RECHARGE_BASIC;
        $model->create_time = time();
        $model->status = RechargeLog::STATUS_CHECKING;
        $model->date = date('Y-m-d', $model->create_time);

        if ($model->save()) {
            return true;
        }
        return false;
    }
}
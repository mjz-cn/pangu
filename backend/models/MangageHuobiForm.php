<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/21
 * Time: 下午3:03
 */

namespace backend\models;


use common\models\records\ConsumeLog;
use yii\base\Model;

class MangageHuobiForm extends Model
{
    public $user_id;
    public $huobi_type;
    public $amount;
    public $desc;

    public function rules()
    {
        return [
            [['user_id', 'huobi_type', 'amount'], 'integer'],
            [['user_id', 'huobi_type', 'amount'], 'required'],
            ['huobi_type', 'in', 'range' => [ConsumeLog::CURRENCY_HUOBI, ConsumeLog::CURRENCY_DIANZIBI]],
            ['desc', 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'huobi_type' => '钱币类型',
            'amount' => '数值',
            'desc' => '描述',
        ];
    }

    public function save() {
        $model = new ConsumeLog();

        $model->user_id = $this->user_id;
        $model->amount = $this->amount;
        $model->currency_type = $this->huobi_type;
        $model->consume_type = ConsumeLog::CONSUME_ADMIN;
        $model->create_time = time();
        $model->date = date('Ymd');
        $model->from_admin_id = \Yii::$app->user->identity->getId();
        $model->desc = $this->generateDesc();

        if ($model->save()) {
            return true;
        } else {
            $this->addErrors($model->errors);
            return false;
        }
    }

    private function generateDesc() {
        $fmt = " %s会员%s; ";
        if (!empty($this->desc)) {
            $fmt .= $this->desc;
        }
        $arg1 = "增加";
        if ($this->amount) {
            $arg1 = "扣除";
        }
        return sprintf($fmt, $arg1, ConsumeLog::getCurrencyText($this->huobi_type));
    }
}
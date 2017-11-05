<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/5
 * Time: 上午12:14
 */

namespace frontend\models;


use common\models\records\ExchangeLog;
use common\models\records\Wallet;
use yii\base\Model;

class ExchangeForm extends Model
{

    public $amount;

    public function rules()
    {
        return [
            ['amount', 'required'],
            ['amount', 'integer']
        ];
    }

    public function save()
    {
        $model = new ExchangeLog();
        $model->user_id = \Yii::$app->user->getId();
        $model->amount = $this->amount;
        $model->create_time = time();
        $model->date = date("Ymd", $model->create_time);
        $model->status = ExchangeLog::STATUS_CHECKING;

        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {
            $wallet = Wallet::getValidWallet($model->user_id);
            $wallet->jiangjin -= $model->amount;
            $model->save();
            $wallet->update(false, ['jiangjin']);
            $dbTransaction->commit();
        } catch (\Exception $e) {

            $dbTransaction->rollBack();
        }

        return $model;
    }
}
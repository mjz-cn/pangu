<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/3
 * Time: 下午6:07
 */

namespace frontend\models;


use common\helpers\TransactionHelper;
use common\models\records\TransactionLog;
use common\models\records\Wallet;
use http\Exception;
use yii\base\Model;

class JiangjinToDianziForm extends Model
{

    public $amount;

    public function rules()
    {
        return [
            [['amount'], 'integer'],
            ['amount', 'integer', 'min' => 0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => '转账金额'
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);
        if ($result) {
            // 验证转账金额是否小于用户奖金余额
            $wallet = Wallet::getValidWallet(\Yii::$app->user->identity->getId());
            if ($this->amount > $wallet->jiangjin) {
                $this->addError('amount', '余额不足');
                return false;
            }
        }
        return $result;
    }

    public function save()
    {
        if (!$this->validate()) {

            return false;
        }
        $userId = \Yii::$app->user->identity->getId();

        $transactionTime = time();
        $transactionDate = date('Ymd', $transactionTime);
        // 生成奖金减少记录
        $transferInLog = new TransactionLog();
        $transferInLog->user_id = $userId;
        $transferInLog->from_user_id = $userId;
        $transferInLog->currency_type = TransactionHelper::CURRENCY_JIANGJIN;
        $transferInLog->transaction_type = TransactionHelper::TRANSACTION_JIANGJIN_TO_DIANZIBI;
        $transferInLog->amount = -1 * $this->amount;
        $transferInLog->create_time = $transactionTime;
        $transferInLog->date = $transactionDate;
        $transferInLog->generateDesc();

        // 转账用户 扣款交易记录
        $transferOutLog = new TransactionLog();
        $transferOutLog->user_id = $userId;
        $transferOutLog->from_user_id = $userId;
        $transferOutLog->currency_type = TransactionHelper::CURRENCY_DIANZIBI;
        $transferOutLog->transaction_type = TransactionHelper::TRANSACTION_JIANGJIN_TO_DIANZIBI;
        $transferOutLog->amount =  $this->amount;
        $transferOutLog->create_time = $transactionTime;
        $transferOutLog->date = $transactionDate;
        $transferOutLog->generateDesc();

        // 被转账用户 入账交易记录

        // 钱包数据修改
        $db = \Yii::$app->db;
        $dbTransaction = $db->beginTransaction();
        try {
            $fromUserWallet = Wallet::getValidWallet($userId);

            $fromUserWallet->dianzi = $this->amount;
            $fromUserWallet->jiangjin -= $this->amount;

            $transferInLog->save();
            $transferOutLog->save();
            $fromUserWallet->update(false, ['dianzi', 'jiangjin']);

            $dbTransaction->commit();
        } catch (Exception $e) {
            \Yii::error("Transfer transaction error, user:  $this->user_id, $this->amount");
            $dbTransaction->rollback();
            return false;
        }
        return true;
    }
}
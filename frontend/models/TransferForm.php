<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/3
 * Time: 下午6:06
 */

namespace frontend\models;


use common\helpers\TransactionHelper;
use common\models\NormalUser;
use common\models\records\TransactionLog;
use common\models\records\Wallet;
use http\Exception;
use yii\base\Model;

class TransferForm extends Model
{
    public $amount;
    public $user_id;

    public function rules()
    {
        return [
            [['amount', 'user_id'], 'integer'],
            [['amount', 'user_id'], 'required'],
            ['amount', 'integer', 'min' => 0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => '转账金额',
            'user_id' => '转入用户'
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);
        if ($result) {
            // 验证转入用户是否存在
            $model = NormalUser::findOne(['id' => $this->user_id]);
            if (empty($model)) {
                $this->addError('user_id', '转入用户不存在');
                return false;
            }
            // 验证转账金额是否小于用户奖金余额
            $wallet = Wallet::getValidWallet(\Yii::$app->user->identity->getId());
            if ($this->amount > $wallet->dianzi) {
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
        $fromUserId = \Yii::$app->user->identity->getId();

        $transactionTime = time();
        $transactionDate = date('Ymd', $transactionTime);
        // 生成转账记录
        $transferInLog = new TransactionLog();
        $transferInLog->user_id = $this->user_id;
        $transferInLog->from_user_id = \Yii::$app->user->identity->getId();
        $transferInLog->currency_type = TransactionHelper::CURRENCY_DIANZIBI;
        $transferInLog->transaction_type = TransactionHelper::TRANSACTION_TRANSFER_IN;
        $transferInLog->amount = $this->amount;
        $transferInLog->create_time = $transactionTime;
        $transferInLog->date = $transactionDate;
        $transferInLog->generateDesc();

        // 转账用户 扣款交易记录
        $transferOutLog = new TransactionLog();
        $transferOutLog->user_id = \Yii::$app->user->identity->getId();
        $transferOutLog->from_user_id = $this->user_id;
        $transferOutLog->currency_type = TransactionHelper::CURRENCY_DIANZIBI;
        $transferOutLog->transaction_type = TransactionHelper::TRANSACTION_TRANSFER_OUT;
        $transferOutLog->amount = -1 * $this->amount;
        $transferOutLog->create_time = $transactionTime;
        $transferOutLog->date = $transactionDate;
        $transferOutLog->generateDesc();

        // 被转账用户 入账交易记录

        // 钱包数据修改
        $db = \Yii::$app->db;
        $dbTransaction = $db->beginTransaction();
        try {
            $fromUserWallet = Wallet::getValidWallet($fromUserId);
            $toUserWallet = Wallet::getValidWallet($this->user_id);

            $fromUserWallet->dianzi -= $this->amount;
            $toUserWallet->dianzi += $this->amount;

            $transferInLog->save();
            $transferOutLog->save();
            $fromUserWallet->update(false, ['dianzi']);
            $toUserWallet->update(false, ['dianzi']);

            $dbTransaction->commit();
        } catch (Exception $e) {
            \Yii::error("Transfer transaction error, user: $fromUserId, $this->user_id, $this->amount");
            $dbTransaction->rollback();
            return false;
        }
        return true;
    }
}
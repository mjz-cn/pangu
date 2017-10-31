<?php

namespace common\models\records;

use common\helpers\TransactionHelper;
use Exception;
use Yii;

/**
 * This is the model class for table "{{%exchange_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $create_time
 * @property string $date
 * @property integer $status
 */
class ExchangeLog extends \yii\db\ActiveRecord
{

    // 审核中
    const STATUS_CHECKING = 0;
    // 通过
    const STATUS_APPROVE = 1;
    // 未通过
    const STATUS_REJECT = 2;


    private $_user;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchange_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date'], 'required'],
            [['user_id', 'amount', 'create_time', 'status'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => '兑现金额',
            'create_time' => 'Create Time',
            'date' => 'Date',
            'status' => '是否审核，0 未审核， 已审核',
        ];
    }

    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = $this->hasOne(User::className(), ['id' => 'user_id']);
        }
        return $this->_user;
    }

    /**
     * @param $exchangeLog  static
     */
    private static function approve($exchangeLog)
    {
        $exchangeLog->status = ExchangeLog::STATUS_APPROVE;

        $transactionLog = new TransactionLog();
        $transactionLog->user_id = $exchangeLog->user_id;
        $transactionLog->amount = -1 * $exchangeLog->amount;
        $transactionLog->currency_type = TransactionHelper::CURRENCY_JIANGJIN;
        $transactionLog->create_time = time();
        $transactionLog->date = date('Y-m-d', $transactionLog->create_time);
        $transactionLog->from_admin_id = \Yii::$app->user->identity->getId();

        $db = \Yii::$app->db;
        $dbTransaction = $db->beginTransaction();
        try {
            $exchangeLog->update();
            $transactionLog->save();

            $dbTransaction->commit();
        } catch (Exception $e) {
            \Yii::error("exchange approve failed", $e);
            $dbTransaction->rollback();
            return '审核失败';
        }
        return null;
    }

    /**
     * @param $exchangeLog  static
     * @return string
     */
    private static function reject($exchangeLog)
    {
        $exchangeLog->status = ExchangeLog::STATUS_REJECT;

        $wallet = Wallet::getValidWallet($exchangeLog->user_id);
        $wallet->jiangjin += $exchangeLog->amount;

        $db = $exchangeLog->getDb();
        $dbTransaction = $db->beginTransaction();
        try {
            $exchangeLog->update();
            $wallet->update();

            $dbTransaction->commit();
        } catch (Exception $e) {
            \Yii::error("exchange approve failed");
            $dbTransaction->rollback();
            return '审核失败';
        }
        return null;
    }

    /**
     * @param $exchangeId     integer   审核单子ID
     * @param $status         integer   申请状态
     * @return string 返回为空，代表操作成功，否则表示失败
     */
    public static function exchange($exchangeId, $status)
    {
        $exchangeLog = ExchangeLog::findOne(['id' => $exchangeId]);
        if ($exchangeLog == null) {
            return '提现申请不存在';
        }
        if ($status == static::STATUS_APPROVE) {
            return static::approve($exchangeLog);
        } elseif ($status == static::STATUS_REJECT) {
            return static::reject($exchangeLog);
        }
        return '提现申请状态错误';
    }
}

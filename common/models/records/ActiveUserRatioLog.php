<?php

namespace common\models\records;

use common\helpers\TransactionHelper;
use common\models\NormalUser;
use http\Exception;
use Yii;

/**
 * This is the model class for table "{{%active_user_ratio_log}}".
 *
 * @property integer $id
 * @property integer $from_user_id
 * @property integer $user_id
 * @property integer $jiangjin
 * @property integer $manage_tax
 * @property integer $chongxiao
 * @property integer $from_admin_id
 * @property integer $status
 * @property string $desc
 * @property integer $create_time
 * @property string $date
 */
class ActiveUserRatioLog extends \yii\db\ActiveRecord
{
    const STATUS_CHECKING = 0;
    const STATUS_APPROVE = 1;
    const STATUS_REJECT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%active_user_ratio_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_user_id', 'user_id', 'jiangjin', 'manage_tax', 'chongxiao', 'from_admin_id', 'status', 'create_time'], 'integer'],
            [['user_id', 'jiangjin', 'manage_tax', 'chongxiao', 'date'], 'required'],
            [['date'], 'safe'],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_user_id' => 'From User ID',
            'user_id' => 'User ID',
            'jiangjin' => 'Jiangjin',
            'manage_tax' => 'Manage Tax',
            'chongxiao' => 'Chongxiao',
            'from_admin_id' => 'From Admin ID',
            'status' => '审核状态, 0 审核中， 1 通过， 2 拒绝',
            'desc' => '此次交易描述',
            'create_time' => 'Create Time',
            'date' => 'Date',
        ];
    }

    /**
     * @return null | NormalUser
     */
    public function getFromUser()
    {
        return NormalUser::findOne(['id' => $this->from_user_id]);
    }

    /**
     * @return null | NormalUser
     */
    public function getUser()
    {
        return NormalUser::findOne(['id' => $this->user_id]);
    }

    public function getStatusText()
    {
        return static::getStatusArr()[$this->status];
    }

    public static function getStatusArr()
    {
        return [
            Baodan::STATUS_CHECKING => '审核中',
            Baodan::STATUS_APPROVE => '已通过',
            Baodan::STATUS_REJECT => '已拒绝',
        ];
    }

    // 生成交易记录并保存到数据库
    public function convertToTransactions()
    {
        $now = time();
        $date = date('Y-m-d', $now);
        $model = $this;
        // 奖金
        $transaction = new TransactionLog();
        $transaction->user_id = $model->user_id;
        $transaction->from_user_id = $model->from_user_id;
        $transaction->transaction_type = TransactionHelper::TRANSACTION_BD_REVENUE;
        $transaction->currency_type = TransactionHelper::CURRENCY_JIANGJIN;
        $transaction->create_time = $now;
        $transaction->date = $date;
        $transaction->desc = $transaction->generateDesc();
        $transaction->amount = $model->jiangjin;

        // 管理税
        $tax = new TransactionLog();
        $tax->user_id = $transaction->user_id;
        $tax->from_user_id = $transaction->from_user_id;
        $tax->transaction_type = TransactionHelper::TRANSACTION_MANAGE_TAX;
        $tax->currency_type = TransactionHelper::CURRENCY_JIANGJIN;
        $tax->amount = $model->manage_tax;
        $tax->create_time = $transaction->create_time;
        $tax->date = $transaction->date;
        $tax->generateDesc();

        // 重复消费
        $chongxiao = new TransactionLog();
        $chongxiao->user_id = $transaction->user_id;
        $chongxiao->from_user_id = $transaction->from_user_id;
        $chongxiao->transaction_type = TransactionHelper::TRANSACTION_CHONGXIAO_TAX;
        $chongxiao->currency_type = TransactionHelper::CURRENCY_CHONGXIAO;
        $chongxiao->amount = $model->chongxiao;
        $chongxiao->create_time = $transaction->create_time;
        $chongxiao->date = $transaction->date;
        $chongxiao->generateDesc();

        $model->status = ActiveUserRatioLog::STATUS_APPROVE;

        $wallet = Wallet::getValidWallet($model->user_id);
        $wallet->jiangjin += $model->jiangjin - $model->manage_tax - $model->chongxiao;
        $wallet->total_jiangjin += $wallet->jiangjin;
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $transaction->save();
            $tax->save();
            $chongxiao->save();
            $model->update(false, ['status']);
            $model->update(false, ['jiangjin', 'total_jiangjin']);

            $dbTransaction->commit();
        } catch (Exception $e) {
            $dbTransaction->rollback();
        }
    }
}

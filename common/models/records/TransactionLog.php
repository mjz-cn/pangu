<?php

namespace common\models\records;

use backend\models\AdminUser;
use common\helpers\TransactionConstants;
use common\helpers\TransactionHelper;
use Yii;

/**
 * This is the model class for table "{{%transaction_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $transaction_type
 * @property integer $currency_type
 * @property double $amount
 * @property integer $from_user_id
 * @property integer $from_admin_id
 * @property string $desc
 * @property integer $create_time
 * @property string $date
 *
 * @property User $user
 */
class TransactionLog extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'transaction_type', 'currency_type', 'date'], 'required'],
            [['id', 'user_id', 'transaction_type', 'currency_type', 'from_user_id', 'from_admin_id', 'create_time'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
            [['desc'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'transaction_type' => '交易类型',
            'currency_type' => '货币类型',
            'amount' => '金额',
            'from_user_id' => 'From User ID',
            'from_admin_id' => 'From Admin ID',
            'desc' => '描述',
            'create_time' => '交易时间',
            'date' => '交易日期',
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);
        if ($result) {
            if (empty($this->from_admin_id) && empty($this->from_user_id) ) {
                $this->addError('from_admin_id from_user_id', '这两者必须设置一个');
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getFromUser() {
        return $this->hasOne(User::className(), ['id' => 'from_user_id']);
    }

    public function getAdmin() {
        return $this->hasOne(Administrator::className(), ['id' => 'from_admin_id']);
    }

    public function currencyText() {
        return TransactionHelper::$CURRENCY_TYPE_ARR[$this->currency_type];
    }

    public function transactionText() {
        return TransactionHelper::$TRANSACTION_TYPE_ARR[$this->transaction_type];
    }

    public function generateDesc() {
        $this->desc = TransactionHelper::generateDescWithModel($this);
        return $this->desc;
    }
}

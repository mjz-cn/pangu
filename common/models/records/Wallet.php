<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "{{%wallet}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $total_jiangjin
 * @property double $jiangjin
 * @property double $dianzi
 * @property double $chongxiao
 * @property double $jifen
 * @property integer $update_time
 */
class Wallet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wallet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'update_time'], 'integer'],
            [['total_jiangjin', 'jiangjin', 'dianzi', 'chongxiao', 'jifen'], 'number'],
            [['user_id'], 'unique'],
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
            'total_jiangjin' => '奖金累积',
            'jiangjin' => '奖金余额',
            'dianzi' => '电子币余额',
            'chongxiao' => '重消余额',
            'jifen' => '积分',
            'update_time' => '更新时间',
        ];
    }
}

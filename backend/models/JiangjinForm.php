<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/29
 * Time: 下午10:00
 */

namespace backend\models;


use common\models\records\TransactionLog;
use common\models\records\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;

class JiangjinForm extends Model
{
    public $detail_time;
    public $user_id;


    public function rules()
    {
        return [
            ['detail_time', 'required'],
            ['detail_time', 'date', 'format' => 'php:Y-m-d'],
            ['user_id', 'integer']
        ];
    }

    public function getMultiUserDataProvider()
    {
        $query = new Query();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $query->select(new Expression('user.username as username, user.real_name as real_name,
                    sum(if (transaction_type=2, `amount`, 0)) as referrer_revenue, 
                    sum(if (transaction_type=3, amount, 0)) as bd_revenue,
                    sum(if (transaction_type=4, amount, 0)) as bd_1_revenue,
                    sum(if (transaction_type=5, amount, 0)) as manage_tax,
                    sum(if (transaction_type=6, amount, 0)) as chongxiao_tax'))
            ->from(TransactionLog::tableName())
            ->innerJoin(User::tableName() . ' user', ['user.id=user_id'])
            ->andFilterWhere(['date' => $this->detail_time]);

        return $dataProvider;
    }

    public function getSingleUserDataProvider()
    {
        $query = TransactionLog::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $query->where(['user_id' => $this->user_id, 'date' => $this->detail_time])
            ->orderBy('create_time desc');

        return $dataProvider;
    }
}
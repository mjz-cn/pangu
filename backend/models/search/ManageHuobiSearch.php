<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午6:56
 */

namespace backend\models\search;


use common\models\records\TransactionLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ManageHuobiSearch extends Model
{
    public $user_id;
    public $start_time;
    // 查询结束时间
    public $end_time;

    public function rules() {
        return [
            [['user_id'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户账号',
            'start_time' => '起始时间',
            'end_time' => '结束时间',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TransactionLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
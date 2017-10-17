<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 下午6:46
 */

namespace backend\models\search;


use common\models\records\ConsumeLog;
use yii\data\ActiveDataProvider;


class BonusSearch extends ConsumeLog
{

    // 用户账号
    public $user_name;
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;
    // 推荐人账号
    public $referrer_name;


    public function rules()
    {
        return [
            [['user_name', 'referrer_name'], 'string', 'max' => 250],
            [['start_time', 'end_time'], 'date']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_name' => '用户账号',
            'referrer_name' => '推荐人账号',
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
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
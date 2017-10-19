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
    public $user_id;
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;
    // 推荐人账号
    public $referrer_id;


    public function rules()
    {
        return [
            [['user_id', 'referrer_id'], 'integer'],
            [['start_time', 'end_time'], 'date']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户账号',
            'referrer_id' => '推荐人账号',
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
             $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->user_id)) {
            $query->filterWhere(['user_id' => $this->user_id]);
        } else if (!empty($this->referrer_id)) {
            $query->filterWhere(['user_id' => $this->referrer_id]);
        }
        $query->filterWhere(['between', 'date', $this->start_time, $this->end_time]);

        return $dataProvider;
    }
}
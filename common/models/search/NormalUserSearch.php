<?php

namespace common\models\search;

use common\models\NormalUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/9/29
 * Time: 下午9:50
 */

class NormalUserSearch extends Model
{
    // 用户账号
    public $user_id;
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;
    // 推荐人账号
    public $referrer_id;
    // 状态
    public $is_actived;

    public function rules()
    {
        return [
            ['is_actived', 'required'],
            [['user_id', 'referrer_id', 'is_actived'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d']
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
        $query = NormalUser::find();

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

        $query->andFilterWhere([
            "is_actived" => $this->is_actived
        ]);

        if (!empty($this->user_id)) {
            $query->andFilterWhere(['id' => $this->user_id]);
        } else if (!empty($this->referrer_id)) {
            $query->andFilterWhere(['id' => $this->referrer_id]);
        }
        $query->andFilterWhere(['between', 'create_time', strtotime($this->start_time), strtotime($this->end_time . ' +1 day')]);

        $query->orderBy('create_time desc');
        return $dataProvider;
    }
}
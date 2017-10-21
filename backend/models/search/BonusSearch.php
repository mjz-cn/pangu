<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/16
 * Time: 下午6:46
 */

namespace backend\models\search;


use common\models\NormalUser;
use yii\data\ActiveDataProvider;


class BonusSearch extends NormalUser
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

        if (!empty($this->user_id)) {
            $query->filterWhere(['id' => $this->user_id]);
        } else if (!empty($this->referrer_id)) {
            $query->filterWhere(['id' => $this->referrer_id]);
        }
        $query->filterWhere(['between', 'create_time', strtotime($this->start_time), strtotime($this->end_time . ' +1 day')]);

        return $dataProvider;
    }
}
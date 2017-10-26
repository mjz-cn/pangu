<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\Baodan;

/**
 * BaodanSearch represents the model behind the search form about `common\models\records\Baodan`.
 */
class BaodanSearch extends Baodan
{
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Baodan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        if ($this->end_time < $this->start_time || empty($this->start_time)) {
            $this->end_time = $this->start_time;
        }

        $query->andFilterWhere(['between', 'create_time', strtotime($this->start_time),
            strtotime($this->end_time . ' +1 day')]);

        return $dataProvider;
    }
}

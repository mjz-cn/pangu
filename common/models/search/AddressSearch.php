<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\Address;

/**
 * AddressSearch represents the model behind the search form about `common\models\records\Address`.
 */
class AddressSearch extends Address
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'province', 'city', 'area', 'phone', 'postcode'], 'integer'],
            [['street'], 'safe'],
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
        $this->load($params);

        return $this->basicSearch();
    }

    public function frontendSearch($params)
    {
        $this->load($params);

        $this->user_id = Yii::$app->user->getId();

        return $this->basicSearch();
    }

    protected function basicSearch()
    {
        $query = Address::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'province' => $this->province,
            'city' => $this->city,
            'area' => $this->area,
            'phone' => $this->phone,
            'postcode' => $this->postcode,
        ]);

        $query->andFilterWhere(['like', 'street', $this->street]);

        $query->orderBy([
            'user_id' => SORT_DESC
        ]);

        return $dataProvider;
    }
}

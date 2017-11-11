<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/24
 * Time: 下午10:48
 */

namespace backend\models\search;


use common\models\records\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BaodanMembersSearch extends Model
{
    //  报单中心ID
    public $bd_id;
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;

    public function rules()
    {
        return [
            [['bd_id'], 'required'],
            [['bd_id'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function search($params) {
        $query = User::find();

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
            'baodan_id' => $this->bd_id,
        ]);

        if ($this->end_time < $this->start_time || empty($this->start_time)) {
            $this->end_time = $this->start_time;
        }

        $query->andFilterWhere(['between', 'create_time', strtotime($this->start_time),
            strtotime($this->end_time . ' +1 day')]);

        return $dataProvider;
    }
}
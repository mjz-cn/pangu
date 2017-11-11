<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/11
 * Time: 下午9:42
 */

namespace backend\models\search;


use common\models\records\ActiveUserRatioLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ActivedUserRatioLogSearch extends Model
{
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;
    public $status;
    public $user_id;
    // 被激活用户
    public $from_user_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'status' => '状态',
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
        $query = ActiveUserRatioLog::find();

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

        $query->andFilterWhere(['between', 'create_time', strtotime($this->start_time),
            strtotime($this->end_time . ' +1 day')]);

        $query->orderBy('create_time desc');
        return $dataProvider;
    }
}

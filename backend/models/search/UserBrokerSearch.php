<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/16
 * Time: 下午9:44
 */

namespace backend\models\search;


use common\models\NormalUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserBrokerSearch extends Model
{

    public $start_time;
    // 查询结束时间
    public $end_time;
    public $user_id;
    public $level;

    public function rules()
    {
        return [
            ['level', 'required'],
            [['user_id', 'level'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function search($params) {
        $this->load($params);

        $query = NormalUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

    }
}
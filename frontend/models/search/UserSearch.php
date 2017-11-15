<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/11/2
 * Time: 上午12:01
 */

namespace frontend\models\search;

use common\helpers\Constants;
use common\models\NormalUser;
use common\models\records\User;
use common\models\UserTree;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * 只显示与此用户相关的用户
 */
class UserSearch extends \yii\base\Model
{
    // 推荐的会员
    const STATUS_REFERRER = 1;
    // 未激活会员
    const STATUS_CHECKING = 2;
    // 已激活会员
    const STATUS_ACTIVED = 3;

    // 查询的用户状态
    public $status;
    // 用户账号
    public $user_id;
    // 查询开始时间
    public $start_time;
    // 查询结束时间
    public $end_time;
    // 推荐人账号
    public $broker_id;
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'broker_id'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d'],
            [['username'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'status' => '状态',
            'user_id' => '用户账号',
            'username' => '用户账号',
            'broker_id' => '领路老师账号',
            'start_time' => '起始时间',
            'end_time' => '结束时间',
        ];
    }

    public function formName()
    {
        return '';
    }

    public function search($params)
    {
        $node = UserTree::findOne(['user_id' => \Yii::$app->user->getId()]);

        if ($node == null) {
            $query = UserTree::find();
            $this->addError('*', 'empty query');
        } else {
            $query = $node->children(\Yii::$app->params['user_tree_depth']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $rootDepth = $node->depth;
        $query->select("u.*, (depth - $rootDepth) as depth");

        $query->from(UserTree::tableName());
        $query->innerJoin(User::tableName() . ' u', 'u.id=user_id');

        $query->andFilterWhere(['broker_id' => $this->broker_id])
            ->andFilterWhere(['username' => $this->username])
            ->andFilterWhere(['between', 'create_time', strtotime($this->start_time),
                strtotime($this->end_time . ' +1 day')]);


        $query->orderBy('depth asc, create_time desc');

        // 返回的model为user，方便前端处理
        $query->modelClass = NormalUser::className();
        return $dataProvider;
    }
}

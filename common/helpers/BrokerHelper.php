<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/28
 * Time: 上午11:29
 */

namespace common\helpers;


use common\models\NormalUser;
use common\models\UserTree;

class BrokerHelper
{

    // 管理奖最高追溯的层数
    CONST REVENUE_UP_LEVEL = 5;

    /**
     * 验证用户是否能成为节点人
     *
     * @param $userId integer
     * @return array 能成为接点人则返回true， 否咋返回false
     */
    public static function validateBroker($userId)
    {
        // 判断用户是否存在
        $model = NormalUser::findOne(['id' => $userId]);
        if (empty($model)) {
            return ['status' => 0, 'msg' => '用户不存在'];
        }
        // 判断用户是否已经有八个子节点存在
        $node = UserTree::findOne(['user_id' => $userId]);
        if (empty($node)) {
            // 创建root
            $root = new UserTree(['user_id' => $userId]);
            $root->makeRoot();
            return ['status' => 1, 'msg' => 'success'];
        }
        $children = $node->children(1)->all();
        if (count($children) >= \Yii::$app->params['broker_child']) {
            return ['status' => 0, 'msg' => '人数已满'];
        }
        return ['status' => 1, 'msg' => 'success'];
    }
}
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

    /**
     * 验证用户是否能成为领路老师
     *
     * @param $userId integer
     * @return array 能成为领路老师  则返回true， 否咋返回false
     */
    public static function validateBroker($userId)
    {
        // 判断用户是否存在
        $model = NormalUser::findOne(['id' => $userId]);
        if (empty($model)) {
            return ['status' => 0, 'msg' => '领路老师不存在'];
        }
        if ($model->is_baned) {
            return ['status' => 0, 'msg' => '领路老师已被冻结'];
        }
        if (!$model->is_actived) {
            return ['status' => 0, 'msg' => '领路老师尚未激活'];
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
        if (count($children) >= \Yii::$app->params['broker_child_cnt']) {
            return ['status' => 0, 'msg' => '人数已满, 请另外选择一个领路老师'];
        }
        return ['status' => 1, 'msg' => 'success'];
    }
}
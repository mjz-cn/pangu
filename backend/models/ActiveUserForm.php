<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/26
 * Time: 上午12:32
 */

namespace backend\models;


use common\models\records\User;
use common\helpers\Constants;
use yii\base\Model;

class ActiveUserForm extends Model
{
    // 用户ID
    public $user_id;
    // 激活状态， 0 空单， 1 实单
    public $active_status;

    public function rules()
    {
        return [
            [['user_id', 'active_status'], 'required'],
            [['user_id', 'active_status'], 'integer'],
            ['active_status', 'in', 'range' => [Constants::NUMBER_FALSE, Constants::NUMBER_TRUE], 'message' => '无效激活状态'],
        ];
    }

    public function active()
    {
        if ($this->active_status == Constants::NUMBER_FALSE) {
            return $this->activeKongDanUser();
        } elseif ($this->active_status == Constants::NUMBER_TRUE) {
            return $this->activeShiDanUser();
        }
        return false;
    }

    // 激活空单用户
    private function activeKongDanUser()
    {
        $model = $this->findUser();
        if ($model == false) {
            return false;
        }
        $model->is_shidan = Constants::NUMBER_FALSE;
        return $model->update(false, ['is_actived', 'is_shidan']);
    }

    // 激活实单用户
    private function activeShiDanUser()
    {
        $model = $this->findUser();
        if ($model == false) {
            return false;
        }
        $model->is_shidan = Constants::NUMBER_TRUE;
        $model->reg_money = 6800.0;

        return $this->a($model) && $model->update('false', ['is_actived', 'is_shidan']);
    }

    // 分成
    private function a($model) {
        // 推荐人，分15％

        // 查看接点人往上两层的网络状况

        // 计算分成，收费

        // 在产生奖金时，同时计算扣税

        // 10% 作为重复消费税

        // 7.5%作为管理税
    }

    private function findUser() {
        $model = User::findOne($this->user_id);
        if (empty($model)) {
            $this->addError('user_id', '用户不存在');
            return false;
        }
        $model->is_actived = Constants::NUMBER_TRUE;
        return $model;
    }
}
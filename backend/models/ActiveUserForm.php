<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/26
 * Time: 上午12:32
 */

namespace backend\models;


use common\helpers\TransactionHelper;
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

        return $this->divideRevenue($model) && $model->update('false', ['is_actived', 'is_shidan']);
    }

    /**
     * 分成
     * @param $userModel User
     */
    private function divideRevenue($userModel)
    {
        $now = time();
        $date = date('Ymd', $now);
        if (empty($userModel->referrer_id)) {
            // 推荐奖
            $referrerTransaction = TransactionHelper::generateThreeTransactionForJiangjin(
                $userModel->id,
                $userModel->referrer_id,
                $userModel->reg_money,
                $now,
                TransactionHelper::TRANSACTION_REFERRER_REVENUE);
            // 保存到数据库

            // 验证有收入的用户，日奖金是否达到一万
        }

        // 一个节点的出现，最多只能产生一个平衡奖

        // 拓展奖, 查找节点人的第一层子节点是否达到平衡

        // 管理奖, 此用户系谱图上几层是否有达到平衡

    }

    private function findUser()
    {
        $model = User::findOne($this->user_id);
        if (empty($model)) {
            $this->addError('user_id', '用户不存在');
            return false;
        }
        $model->is_actived = Constants::NUMBER_TRUE;
        return $model;
    }
}
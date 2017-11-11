<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/26
 * Time: 上午12:32
 */

namespace backend\models;


use common\helpers\BrokerHelper;
use common\helpers\TransactionHelper;
use common\models\records\ActiveUserRatioLog;
use common\models\records\User;
use common\helpers\Constants;
use common\models\UserTree;
use http\Exception;
use Yii;
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

    /*
     * 激活实单用户
     * 目前激活，暂不发放奖金，到月底由财务审核之后再进行发放
     * 生成激活实单用户记录，记录要接受奖金的用户，金额，税费，重消, 等月底财务进行审核
     *
     *  from_user_id user_id jiangjin manage_tax chongxiao create_time status
     *
     */
    private function activeShiDanUser()
    {
        $model = $this->findUser();
        if ($model == false) {
            return false;
        }
        $model->is_shidan = Constants::NUMBER_TRUE;
        $model->reg_money = Yii::$app->params['reg_money'];


        $ratioLogArr = [];
        $userNode = UserTree::findOne(['user_id' => $model->id]);
        $parentNodes = $userNode->parents(Yii::$app->params['user_tree_depth'])->all();
        // 一级分成
        $now = time();
        foreach ($parentNodes as $parentNode) {
            $level = ($parentNode->depth - $userNode->depth) + Yii::$app->params['user_tree_depth'] + 1;
            $ratio = Yii::$app->params['user_tree_ratio'];
            $jiangjin = $model->reg_money * $ratio;

            $ratioLog = new ActiveUserRatioLog();
            $ratioLog->user_id = $parentNode->user_id;
            $ratioLog->from_user_id = $model->id;
            $ratioLog->status = ActiveUserRatioLog::STATUS_CHECKING;
            $ratioLog->create_time = $now;
            $ratioLog->date = date('Y-m-d', $now);
            $ratioLog->manage_tax = $jiangjin * TransactionHelper::RATIO_MANAGE_TAX;
            $ratioLog->chongxiao = $jiangjin * TransactionHelper::RATIO_CHONGXIAO_TAX;
            $ratioLog->jiangjin = $jiangjin;
            $ratioLog->desc = $level;

            $ratioLogArr[] = $ratioLog;
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($ratioLogArr as $ratioLog) {
                $ratioLog->save();
            }
            $model->update();

            $dbTransaction->commit();
        } catch (Exception $e) {
            $dbTransaction->rollback();
        }

        return true;
    }

    /**
     * 对注册资金进行分成
     *
     * @param $userModel User
     */
    private function shareRegMoney($userModel)
    {
        $now = time();
        $date = date('Ymd', $now);
        if (empty($userModel->referrer_id)) {
            // 推荐奖
            TransactionHelper::saveRevenueTransaction(
                $userModel->id,
                $userModel->referrer_id,
                $userModel->reg_money,
                $now,
                TransactionHelper::TRANSACTION_REFERRER_REVENUE);
        }

        // 一个节点的出现，最多只能产生一个平衡奖
        // 检查是否有左兄弟节点
        $userTreeNode = UserTree::findOne(['user_id' => $userModel->id]);
        $prevNode = $userTreeNode->prev()->one();

        if (empty($prevNode)) {
            // 检查整颗树中达到平衡的子树, 管理奖
            for ($i = 2; $i < BrokerHelper::REVENUE_UP_LEVEL; $i++) {
                $parent = $userTreeNode->parents(i)->one();
                $children = $parent->children($i)->all();
                if (count($children) == 2) {
                    TransactionHelper::saveRevenueTransaction(
                        $userModel->id,
                        $userModel->referrer_id,
                        $userModel->reg_money,
                        $now,
                        TransactionHelper::TRANSACTION_BD_REVENUE);
                    break;
                }
            }

        } else {
            // 拓展奖, 左边有兄弟节点，说明第一次达到平衡，给节点人，增加奖金
            TransactionHelper::saveRevenueTransaction(
                $userModel->id,
                $userModel->referrer_id,
                $userModel->reg_money,
                $now,
                TransactionHelper::TRANSACTION_BD_REVENUE_1);
        }
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
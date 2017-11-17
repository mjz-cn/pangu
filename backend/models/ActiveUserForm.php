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
            $depth = $userNode->depth - $parentNode->depth;
            if (!isset(Yii::$app->params['user_tree_ratio'][$depth])) {
                continue;
            }
            $ratio = Yii::$app->params['user_tree_ratio'][$depth];
            $jiangjin = $model->reg_money * $ratio;

            $ratioLog = new ActiveUserRatioLog();
            $ratioLog->user_id = $parentNode->user_id;
            $ratioLog->from_user_id = $model->id;
            $ratioLog->status = ActiveUserRatioLog::STATUS_CHECKING;
            $ratioLog->create_time = $now;
            $ratioLog->date = date('Y-m-d', $now);
            $ratioLog->jiangjin = $jiangjin;
            $ratioLog->depth_type = $depth;
            $ratioLog->desc = '第' . $depth . '层';

            $ratioLogArr[] = $ratioLog;
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($ratioLogArr as $ratioLog) {
                $ratioLog->save();
                if ($ratioLog->errors) {
                    var_dump($ratioLog->errors);exit;
                }
            }
            $model->update();

            $dbTransaction->commit();
        } catch (Exception $e) {
            $dbTransaction->rollback();
        }

        return true;
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
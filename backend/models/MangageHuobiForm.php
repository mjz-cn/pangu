<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/21
 * Time: 下午3:03
 */

namespace backend\models;


use common\helpers\TransactionHelper;
use common\models\records\TransactionLog;
use common\models\records\Wallet;
use http\Exception;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class MangageHuobiForm extends Model
{
    public $user_id;
    public $huobi_type;
    public $amount;
    public $desc;

    public function rules()
    {
        return [
            [['user_id', 'huobi_type', 'amount'], 'integer'],
            [['user_id', 'huobi_type', 'amount'], 'required'],
            ['huobi_type', 'in', 'range' => [TransactionHelper::CURRENCY_JIANGJIN, TransactionHelper::CURRENCY_DIANZIBI]],
            ['desc', 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'huobi_type' => '钱币类型',
            'amount' => '数值',
            'desc' => '描述',
        ];
    }

    public function save()
    {
        // 生成交易记录
        $transactionLog = new TransactionLog();
        $transactionLog->user_id = $this->user_id;
        $transactionLog->amount = $this->amount;
        $transactionLog->currency_type = $this->huobi_type;
        $transactionLog->transaction_type = TransactionHelper::TRANSACTION_ADMIN;
        $transactionLog->create_time = time();
        $transactionLog->date = date('Ymd');
        $transactionLog->from_admin_id = \Yii::$app->user->identity->getId();
        $transactionLog->desc = $this->generateDesc();

        // 修改钱包中数据
        $wallet = Wallet::getValidWallet($transactionLog->user_id);
        switch ($this->huobi_type) {
            case TransactionHelper::CURRENCY_JIANGJIN:
                $wallet->addJiangjin($transactionLog->amount);
                break;
            case TransactionHelper::CURRENCY_DIANZIBI:
                $wallet->addDianzi($transactionLog->amount);
                break;
            default:
                throw new BadRequestHttpException('无效的货币类型');
        }

        $wallet->amount += $transactionLog->amount;

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $wallet->update();
            $transactionLog->save();

            $dbTransaction->commit();
            return true;
        } catch (Exception $e) {
            $dbTransaction->rollback();
            Yii::error('manage huobi save occur an error');
            return false;
        }
    }

    private function generateDesc()
    {
        $fmt = " %s会员%s; ";
        if (!empty($this->desc)) {
            $fmt .= $this->desc;
        }
        $arg1 = "增加";
        if ($this->amount) {
            $arg1 = "扣除";
        }
        return sprintf($fmt, $arg1, TransactionHelper::currencyTypeText($this->huobi_type));
    }
}
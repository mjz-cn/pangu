<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/27
 * Time: 上午12:34
 */

namespace common\helpers;


use common\models\records\TransactionLog;
use common\models\records\User;
use common\models\records\Wallet;
use yii\base\Exception;

class TransactionHelper
{

    const DAILY_JIANGJIN = 10000;

    /**
     * 货币类型
     */
    // 货币
    const CURRENCY_HUOBI = 1;
    // 电子币
    const CURRENCY_DIANZIBI = 2;
    // 奖金
    const CURRENCY_JIANGJIN = 3;
    // 消费币
    const CURRENCY_CHONGXIAO = 4;

    public static $CURRENCY_TYPE_ARR = [
        TransactionHelper::CURRENCY_HUOBI => '货币',
        TransactionHelper::CURRENCY_DIANZIBI => '电子币',
        TransactionHelper::CURRENCY_JIANGJIN => '奖金币',
        TransactionHelper::CURRENCY_CHONGXIAO => '消费币',
    ];

    /**
     *  交易类型
     */
    const TRANSACTION_ADMIN = 1;
    // 推荐提成奖 15%
    const TRANSACTION_REFERRER_REVENUE = 2;
    // 系谱图左右子节点第一次平衡奖 35%
    const TRANSACTION_BD_REVENUE = 3;
    // 系谱图左右树平衡奖 10%
    const TRANSACTION_BD_REVENUE_1 = 4;
    // 所得奖金，扣除 10% 作为重复消费税，7.5%作为管理税
    const TRANSACTION_MANAGE_TAX = 5;
    const TRANSACTION_CHONGXIAO_TAX = 6;
    // 提现税收 3%
    const TRANSACTION_EXCHANGE = 7;
    // 转账转出
    const TRANSACTION_TRANSFER_IN = 8;
    // 转账转入
    const TRANSACTION_TRANSFER_OUT = 9;
    const TRANSACTION_JIANGJIN_TO_DIANZIBI = 10;
    const TRANSACTION_RECHARGE = 11;

    public static $TRANSACTION_TYPE_ARR = [
        TransactionHelper::TRANSACTION_ADMIN => "来自管理员",
        TransactionHelper::TRANSACTION_REFERRER_REVENUE => "推荐提成",
        TransactionHelper::TRANSACTION_BD_REVENUE => "拓展奖",
        TransactionHelper::TRANSACTION_BD_REVENUE_1 => "管理奖",
        TransactionHelper::TRANSACTION_MANAGE_TAX => "管理税",
        TransactionHelper::TRANSACTION_CHONGXIAO_TAX => "重复消费",
        TransactionHelper::TRANSACTION_EXCHANGE => "提现税",
        TransactionHelper::TRANSACTION_TRANSFER_IN => "转账-转入",
        TransactionHelper::TRANSACTION_TRANSFER_OUT => "转账-转出",
        TransactionHelper::TRANSACTION_JIANGJIN_TO_DIANZIBI => "奖金转电子币",
        TransactionHelper::TRANSACTION_RECHARGE => "重复报单",
    ];

    /**
     * 分成比例
     **/
    // 推荐提成奖 15%
    const RATIO_REFERRER_REVENUE = 0.15;
    // 系谱图左右子节点第一次平衡奖 35%
    const RATIO_BD_REVENUE = 0.35;
    // 系谱图左右树平衡奖 10%
    const RATIO_BD_REVENUE_1 = 0.10;
    // 所得奖金，扣除 10% 作为重复消费税，7.5%作为管理税
    const RATIO_MANAGE_TAX = 0.75;
    const RATIO_CHONGXIAO_TAX = 0.10;
    // 提现税收 3%
    const RATIO_EXCHANGE_TAX = 0.03;

    /**
     * @param $model TransactionLog
     * @return string
     */
    public static function generateDescWithModel($model)
    {
        return self::generateDesc($model->from_user_id, $model->user_id, $model->transaction_type, $model->currency_type,
            $model->amount);
    }

    public static function generateDesc($from_user_id, $user_id, $transaction_type, $currency_type, $amount)
    {
        $desc = static::transactionTypeText($transaction_type) . ' ';
        if ($from_user_id) {
            $desc .= '由';
        }
        $desc .= "$amount" . static::currencyTypeText($currency_type);
        return $desc;

    }

    public static function transactionTypeText($transactionType)
    {
        return static::$TRANSACTION_TYPE_ARR[$transactionType];
    }

    public static function currencyTypeText($currencyType)
    {
        return static::$CURRENCY_TYPE_ARR[$currencyType];
    }

    /**
     * 生成奖金交易记录
     * 当有奖金产生的时候，也会同时产生，管理税，重复消费税
     * @param $userId               integer     交易对象
     * @param $fromUserId           integer     产生交易的对象
     * @param $originAmount         integer     原始额度，不同的交易类型产生不同的奖金
     * @param $transactionTime      integer     交易时间
     * @param $transactionType      integer     交易类型
     * @return TransactionLog
     * @throws \Exception       当交易类型不属于
     * [TRANSACTION_REFERRER_REVENUE, TRANSACTION_BD_REVENUE, TRANSACTION_BD_REVENUE_1] 时，会抛出异常
     */
    public static function generateJiangjinTransaction($userId, $fromUserId, $originAmount, $transactionTime, $transactionType)
    {
        $transaction = new TransactionLog();
        $transaction->user_id = $userId;
        $transaction->from_user_id = $fromUserId;
        $transaction->transaction_type = $transactionType;
        $transaction->currency_type = TransactionHelper::CURRENCY_JIANGJIN;
        $transaction->create_time = $transactionTime;
        $transaction->date = date("Ymd", $transactionTime);
        $transaction->desc = $transaction->generateDesc();

        switch ($transactionType) {
            case TransactionHelper::TRANSACTION_REFERRER_REVENUE:
                $amount = $originAmount * TransactionHelper::RATIO_REFERRER_REVENUE;
                break;
            case TransactionHelper::TRANSACTION_BD_REVENUE:
                $amount = $originAmount * TransactionHelper::RATIO_BD_REVENUE;
                break;
            case TransactionHelper::TRANSACTION_BD_REVENUE_1:
                $amount = $originAmount * TransactionHelper::RATIO_BD_REVENUE_1;
                break;
            default:
                throw new \Exception("Can not create jiangjin transaction for type " . $transactionType);
        }
        $transaction->amount = $amount;
        return $transaction;
    }


    /**
     * 为奖金交易记录生成管理税和重复消费交易记录
     *
     * @param $transaction TransactionLog       奖金交易记录
     *
     * @return array
     */
    public static function generateTaxTransactionForJiangjin($transaction)
    {
        // 扣税
        $tax = new TransactionLog();
        $tax->user_id = $transaction->user_id;
        $tax->from_user_id = $transaction->from_user_id;
        $tax->transaction_type = TransactionHelper::TRANSACTION_MANAGE_TAX;
        $tax->currency_type = TransactionHelper::CURRENCY_JIANGJIN;
        $tax->amount = -1 * $transaction->amount * TransactionHelper::RATIO_MANAGE_TAX;
        $tax->create_time = $transaction->create_time;
        $tax->date = $transaction->date;
        $tax->generateDesc();

        // 重复消费
        $chongxiao = new TransactionLog();
        $chongxiao->user_id = $transaction->user_id;
        $chongxiao->from_user_id = $transaction->from_user_id;
        $chongxiao->transaction_type = TransactionHelper::TRANSACTION_CHONGXIAO_TAX;
        $chongxiao->currency_type = TransactionHelper::CURRENCY_CHONGXIAO;
        $chongxiao->amount = -1 * $transaction->amount * TransactionHelper::RATIO_CHONGXIAO_TAX;
        $chongxiao->create_time = $transaction->create_time;
        $chongxiao->date = $transaction->date;
        $chongxiao->generateDesc();
        return [$tax, $chongxiao];
    }


    /**
     *
     * @param $userId               integer     交易对象
     * @param $fromUserId           integer     产生交易的对象
     * @param $originAmount         integer     原始额度，不同的交易类型产生不同的奖金
     * @param $transactionTime      integer     交易时间
     * @param $transactionType      integer     交易类型
     * @return array 三条交易记录，奖金交易记录，管理税，重消交易记录
     */
    public static function generateThreeTransactionForJiangjin($userId, $fromUserId, $originAmount, $transactionTime, $transactionType)
    {
        $jiangjinTransaction = static::generateJiangjinTransaction($userId, $fromUserId, $originAmount, $transactionTime, $transactionType);
        return array_merge([$jiangjinTransaction], static::generateTaxTransactionForJiangjin($jiangjinTransaction));
    }


    /**
     * 保存产生奖金的交易记录, 如果用户当日收到的奖金已经超过1万，
     * 则收到的奖金置为0
     *
     * @param $userId               integer     交易对象
     * @param $fromUserId           integer     产生交易的对象
     * @param $originAmount         integer     原始额度，不同的交易类型产生不同的奖金
     * @param $transactionTime      integer     交易时间
     * @param $transactionType      integer     交易类型
     * @return int|mixed            返回用户今天收到的奖金数量
     */
    public static function saveRevenueTransaction($userId, $fromUserId, $originAmount, $transactionTime, $transactionType)
    {
        // 获取用户今天的奖金
        $todayJiangjin = TransactionLog::find()->where([
                'user_id' => $userId,
                'currency_type' => static::CURRENCY_JIANGJIN,
                'date' => date("Ymd", $transactionTime)]
        )->sum("amount");

        $transactions = static::generateThreeTransactionForJiangjin($userId, $fromUserId, $originAmount, $transactionTime, $transactionType);
        $totalAmount = 0;

        $wallet = Wallet::getValidWallet($userId);

        $db = \Yii::$app->db;
        $dbTransaction = $db->beginTransaction();
        try {
            foreach ($transactions as $transactionLog) {
                if ($todayJiangjin >= static::DAILY_JIANGJIN) {
                    $transactionLog->amout = 0.0;
                }
                if ($transactionLog->currency_type == TransactionHelper::CURRENCY_CHONGXIAO) {
                    $wallet->chongxiao += $transactionLog->amout;
                }
                $totalAmount += $transactionLog->amout;
                $transactionLog->save();
            }
            // 更新钱包, 奖金, 重消
            $wallet->jiangjin += $totalAmount;
            $wallet->update();

            $dbTransaction->commit();
        } catch (Exception $e) {
            \Yii::error("save revenue transaction error, $userId, $fromUserId, $originAmount, $transactionTime, $transactionType");
            $dbTransaction->rollback();
        }
        return $todayJiangjin + $totalAmount;
    }
}
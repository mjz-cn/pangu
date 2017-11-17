<?php

namespace common\models\records;

use common\helpers\BrokerHelper;
use common\models\UserTree;
use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $phone
 * @property string $wechat
 * @property integer $qq
 * @property integer $role
 * @property integer $reg_ip
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property string $image
 * @property integer $broker_id
 * @property string $broker_path
 * @property integer $referrer_id
 * @property integer $baodan_id
 * @property integer $level
 * @property string $real_name
 * @property integer $gender
 * @property string $card_id
 * @property string $bank_account
 * @property string $bank_name
 * @property string $bank_username
 * @property integer $status
 * @property integer $update_time
 * @property integer $create_time
 * @property integer $is_shidan
 * @property integer $is_baned
 * @property integer $is_actived
 * @property float $reg_money
 * @property integer $reg_user_id
 * @property integer $province
 * @property integer $city
 * @property integer $area
 *
 * @property Address[] $addresses
 * @property TransactionLog[] $consumeLogs
 * @property NormalUserInfo $normalUserInfo
 */
class User extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_NORMAL = 1;
    const STATUS_ACTIVED = 2;
    const STATUS_NOT_ACTIVED = 3;

    const LEVEL_UNSET = 0;
    const LEVEL_BAIYIN = 1;
    const LEVEL_HUANGJIN = 2;
    const LEVEL_BAIJIN = 3;
    const LEVEL_BAOSHI = 4;
    const LEVEL_ZUANSHI = 5;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const LEVEL_ARR = [
        User::LEVEL_UNSET => '普通',
        User::LEVEL_BAIYIN => '白银',
        User::LEVEL_HUANGJIN => '黄金',
        User::LEVEL_BAIJIN => '白金',
        User::LEVEL_BAOSHI => '宝石',
        User::LEVEL_ZUANSHI => '钻石',
    ];

    // 仅用于展示当前用户在系谱图中与某个父节点的相对深度
    public $depth;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'phone', 'salt', 'email', 'real_name', 'gender', 'card_id', 'bank_account', 'broker_id',
                'province',
                'bank_name', 'bank_username'], 'required'],
            [['role', 'reg_ip', 'last_login_time', 'last_login_ip', 'broker_id', 'referrer_id',
                'baodan_id', 'gender', 'level', 'status', 'update_time', 'create_time', 'is_shidan',
                'is_baned', 'is_actived', 'reg_user_id', 'qq', 'city', 'area'],
                'integer'],
            [['reg_money'], 'number'],
            [['username', 'password', 'salt', 'email', 'image', 'real_name', 'bank_account', 'bank_name', 'bank_username'], 'string', 'max' => 255],
            [['phone', 'wechat'], 'string', 'max' => 15],
//            [['password_1'], 'string', 'min' => 6],
            [['broker_path', 'card_id'], 'string', 'max' => 20],
            [['username'], 'unique'],
            ['broker_id', 'validateBroker'],
            ['password', 'required', 'on' => static::SCENARIO_CREATE]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
//            'password_1' => '密码',
            'salt' => 'Salt',
            'email' => '邮箱',
            'phone' => '电话',
            'role' => 'Role',
            'reg_ip' => '注册IP',
            'last_login_time' => '上一次登陆时间',
            'last_login_ip' => '上一次登陆IP',
            'image' => 'Image',
            'broker_id' => '领路老师',
            'broker_path' => '系谱图路径',
            'referrer_id' => '推荐人',
            'real_name' => '真实姓名',
            'gender' => '性别',
            'card_id' => '身份证号',
            'bank_account' => '银行账号',
            'bank_name' => '开户行',
            'bank_username' => '银行帐户姓名',
            'status' => 'Status',
            'update_time' => '更新时间',
            'create_time' => '注册时间',
            'baodan_id' => '报单中心',
            'level' => '会员级别',
            'is_shidan' => '是否为实单',
            'is_baned' => '是否被封禁',
            'is_actived' => '是否激活',
            'reg_user_id' => '开通此用户的账号',
            'reg_money' => '注册金额'
        ];
    }

    public function validateBroker()
    {
        $result = BrokerHelper::validateBroker($this->broker_id);
        if ($result['status'] !== 1) {
            $this->addError('broker_id', $result['msg']);
        }
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result) {
            if ($insert) {
                $this->create_time = time();
            } else {
                $this->update_time = time();
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionLogs()
    {
        return $this->hasMany(TransactionLog::className(), ['user_id' => 'id']);
    }

    public function getWallet()
    {
        return $this->hasOne(Wallet::className(), ['user_id' => 'id']);
    }

    /**
     * 获取领路老师
     */
    public function getBroker()
    {
        if (empty($this->broker_id)) {
            return null;
        }
        return static::findOne(['id' => $this->broker_id]);
    }

    public function getBaodan()
    {
        if ($this->_baodan === null) {
            $model = Baodan::findOne(['user_id' => $this->id]);
            $this->_baodan = $model === null ? false : $model;
        }

        return $this->_baodan === false ? null : $this->_baodan;
    }

    public function getStatusText()
    {
        if ($this->status == static::STATUS_NORMAL) {
            return '正常';
        }
        return static::getStatusArr()[$this->status];
    }

    public static function getStatusArr()
    {
        return [
            static::STATUS_DELETED => '封禁',
            static::STATUS_ACTIVED => '已激活',
            static::STATUS_NOT_ACTIVED => '未激活'
        ];
    }

    public function getLevelText()
    {
        return static::LEVEL_ARR[$this->level];
    }

    public static function getLevelArr()
    {
        return static::LEVEL_ARR;
    }

    /**
     * 返回用户username
     * @param $id
     * @return null|string
     */
    public static function getUsername($id)
    {
        $user = static::findOne(['id' => $id]);
        if ($user) {
            return $user->username;
        }
        return null;
    }

    public function getDetailAddress()
    {
        $regions = Region::find()->select('fullname')
            ->where(['code' => [$this->province, $this->city, $this->area]])
            ->orderBy('code asc')
            ->all();

        $da = '';
        foreach ($regions as $region) {
            $da .= $region->fullname . '/';
        }
        return $da;
    }

    public function getChild()
    {
        $data = [
            'level1' => 0,
            'level2' => 0,
            'level3' => 0,
            'total' => 0,
        ];
        $node = UserTree::findOne(['user_id' => $this->id]);
        if (empty($node)) {
            return $data;
        }
        $children = $node->children(Yii::$app->params['user_tree_depth'])->all();
        if ($children == null || empty($children)) {
            return $data;
        }
        foreach ($children as $child) {
            $data['level' . ($child->depth - $node->depth)] += 1;
            $data['total'] += 1;
        }
        return $data;
    }

    /**
     * 白银级别：0-5席加盟人员
     * 黄金级别：6-10席加盟人员
     * 白金级别：11-20席加盟人员
     * 宝石级别：21-40席加盟人员
     * 钻石级别：大于40席加盟人员
     *
     * @param $num integer 人数
     * @return int 加盟商人数对应的级别
     */
    public static function countLevel($num)
    {
        //
        $i = intval(($num - 1) / 5) + 1;

        return $i > 8 ? 8 : $i;
    }
}

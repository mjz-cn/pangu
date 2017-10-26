<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/26
 * Time: 上午12:32
 */

namespace backend\models;


use common\utils\Constants;
use yii\base\Model;

class ActiveUserForm extends Model
{
    public $user_id;
    public $active_status;

    public function rules()
    {
        return [
            [['user_id', 'active_status'], 'required'],
            [['user_id', 'active_status'], 'integer'],
            ['active_status', 'in', 'range' => [Constants::NUMBER_FALSE, Constants::NUMBER_TRUE]],
        ];
    }

    public function save() {

        return true;
    }
}
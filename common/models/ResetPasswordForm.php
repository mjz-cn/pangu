<?php
namespace common\models;

use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $user_id;
    public $password;
    public $password_repeat;

    /**
     * @var \common\models\NormalUser
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'user_id', 'password_repeat'], 'required'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            [['user_id'], 'integer'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"密码不一致" ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'password' => '密码',
            'password_repeat' => '重复密码'
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->findModel($this->user_id);
        $user->setPassword($this->password);

        return $user->update(false, ['password']);
    }

    private function findModel($id)
    {
        $model = NormalUser::findOne($id);
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }
}

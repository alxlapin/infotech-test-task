<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;

class SignUpForm extends Model
{
    public string $username = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'string', 'min' => 6, 'max' => 255],
            [['password'], 'string', 'min' => 6],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
        ];
    }

    public function signUp(): bool
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->password = $this->password;
            $user->generateAuthKey();

            if ($user->save()) {
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $user->id);
                return true;
            }
        }

        return false;
    }
}
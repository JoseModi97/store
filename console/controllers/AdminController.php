<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

class AdminController extends Controller
{
    public function actionCreate()
    {
        $username = $this->prompt('Username:');
        $email = $this->prompt('Email:');
        $password = $this->prompt('Password:');

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->role = User::ROLE_ADMIN;

        if ($user->save()) {
            $this->stdout("Admin user created successfully.\n");
        } else {
            $this->stderr("Error creating admin user:\n");
            foreach ($user->getErrors() as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stderr("- $attribute: $error\n");
                }
            }
        }
    }
}

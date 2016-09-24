<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\models\UsersModel;

class UserInfoWidget extends Widget
{
    use ExceptionsTrait;
    
    public function run()
    {
        try {
            if (\Yii::$app->user->isGuest) {
                $user = \Yii::t('base', 'Guest');
            } else {
                if (\Yii::$app->user->identity && \Yii::$app->user->identity instanceof UsersModel) {
                    if (!empty(\Yii::$app->user->identity->name)) {
                        $user = \Yii::$app->user->identity->name;
                    } else {
                        $user = \Yii::$app->user->identity->emails->email;
                    }
                }
            }
            
            return '<p>' . \Yii::t('base', "Hello, {placeholder}!", ['placeholder'=>$user]) . '</p>';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\exceptions\ExceptionsTrait;
use app\helpers\HashHelper;

/**
 * Проверяет валидность IP сессии для зарегистрированного пользователя
 */
class UserIpFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    public function beforeAction($action)
    {
        try {
            if (\Yii::$app->user->isGuest === false) {
                $session = \Yii::$app->session;
                $session->open();
                $sessionIP = $session->get(HashHelper::createSessionIpKey());
                $session->close();
                
                $requestIP = \Yii::$app->request->getUserIP();
                
                if ($sessionIP !== $requestIP) {
                    \Yii::$app->user->logout();
                }
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

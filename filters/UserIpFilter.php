<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\exceptions\ExceptionsTrait;
use app\helpers\HashHelper;
use app\finders\UserIpSessionFinder;

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
                $finder = \Yii::$app->registry->get(UserIpSessionFinder::class, [
                    'key'=>HashHelper::createSessionIpKey()
                ]);
                $userIpModel = $finder->find();
                
                if (empty($userIpModel) || empty($userIpModel->ip)) {
                    \Yii::$app->user->logout();
                }
                
                $requestIP = \Yii::$app->request->getUserIP();
                
                if (empty($requestIP) || ($userIpModel->ip !== $requestIP)) {
                    \Yii::$app->user->logout();
                }
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

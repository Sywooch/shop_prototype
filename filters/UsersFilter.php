<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\helpers\UserAuthenticationHelper;

/**
 * Заполняет объект \Yii::$app->user данными сесии
 */
class UsersFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает из сессионного хранилища объект, хранящий данные юзера
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            if (empty(\Yii::$app->params['usersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная usersKeyInSession!');
            }
            
            $session = \Yii::$app->session;
            if ($session->has(\Yii::$app->params['usersKeyInSession'])) {
                $session->open();
                if (!UserAuthenticationHelper::fill($session->get(\Yii::$app->params['usersKeyInSession']))) {
                    throw new ErrorException('Ошибка при обновлении данных \Yii::$app->user!');
                }
                $session->close();
            } else {
                \Yii::configure(\Yii::$app->user, ['login'=>\Yii::$app->params['nonAuthenticatedUserLogin']]);
            }
            
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Сохраняет текущее состояние \Yii::$app->user
     * @param $action выполняемое в данный момент действие
     * @param $result результирующая строка перед отправкой в браузер клиента
     * @return parent result
     */
    public function afterAction($action, $result)
    {
        try {
            if (empty(\Yii::$app->params['usersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная usersKeyInSession!');
            }
            
            $session = \Yii::$app->session;
            if (\Yii::$app->user->login != \Yii::$app->params['nonAuthenticatedUserLogin']) {
                $session->open();
                $session->set(\Yii::$app->params['usersKeyInSession'], \Yii::$app->user);
                $session->close();
            }
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

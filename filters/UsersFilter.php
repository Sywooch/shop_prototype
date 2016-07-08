<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\helpers\UserAuthenticationHelper;
use app\models\UsersModel;

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
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.id')) {
                
                $session->open();
                
                $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_DB]);
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.id')) {
                    $usersModel->id = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.id');
                }
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.login')) {
                    $usersModel->login = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.login');
                }
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.name')) {
                    $usersModel->name = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.name');
                }
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.surname')) {
                    $usersModel->surname = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.surname');
                }
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.id_emails')) {
                    $usersModel->id_emails = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.id_emails');
                }
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.id_phones')) {
                    $usersModel->id_phones = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.id_phones');
                }
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.user.id_address')) {
                    $usersModel->id_address = $session->get(\Yii::$app->params['usersKeyInSession'] . '.user.id_address');
                }
                
                if (!UserAuthenticationHelper::fill($usersModel)) {
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
                
                if (!empty(\Yii::$app->user->id)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.id', \Yii::$app->user->id);
                }
                if (!empty(\Yii::$app->user->login)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.login', \Yii::$app->user->login);
                }
                if (!empty(\Yii::$app->user->name)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.name', \Yii::$app->user->name);
                }
                if (!empty(\Yii::$app->user->surname)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.surname', \Yii::$app->user->surname);
                }
                if (!empty(\Yii::$app->user->id_emails)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.id_emails', \Yii::$app->user->id_emails);
                }
                if (!empty(\Yii::$app->user->id_phones)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.id_phones', \Yii::$app->user->id_phones);
                }
                if (!empty(\Yii::$app->user->id_address)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.user.id_address', \Yii::$app->user->id_address);
                }
                
                $session->close();
            }
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

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
            if (empty(\Yii::$app->params['nonAuthenticatedUserLogin'])) {
                throw new ErrorException('Не установлена переменная nonAuthenticatedUserLogin!');
            }
            
            $session = \Yii::$app->session;
                
            $session->open();
            
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_DB, 'login'=>\Yii::$app->params['nonAuthenticatedUserLogin']]);
            
            /*foreach (\Yii::$app->params['sessionKeysForUser'] as $key) {
                if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.' . $key)) {
                    $usersModel->$key = $session->get(\Yii::$app->params['usersKeyInSession'] . '.' . $key);
                }
            }*/
            
            if ($session->has(\Yii::$app->params['usersKeyInSession'])) {
                $usersModel->attributes = $session->get(\Yii::$app->params['usersKeyInSession']);
            }
            
            /*if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.id')) {
                $usersModel->id = $session->get(\Yii::$app->params['usersKeyInSession'] . '.id');
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.login')) {
                $usersModel->login = $session->get(\Yii::$app->params['usersKeyInSession'] . '.login');
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.name')) {
                $usersModel->name = $session->get(\Yii::$app->params['usersKeyInSession'] . '.name');
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.surname')) {
                $usersModel->surname = $session->get(\Yii::$app->params['usersKeyInSession'] . '.surname');
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.id_emails')) {
                $usersModel->id_emails = $session->get(\Yii::$app->params['usersKeyInSession'] . '.id_emails');
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.id_phones')) {
                $usersModel->id_phones = $session->get(\Yii::$app->params['usersKeyInSession'] . '.id_phones');
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.id_address')) {
                $usersModel->id_address = $session->get(\Yii::$app->params['usersKeyInSession'] . '.id_address');
            }*/
            
            $session->close();
            
            if (!UserAuthenticationHelper::fill($usersModel)) {
                throw new ErrorException('Ошибка при обновлении данных \Yii::$app->user!');
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
                
                /*foreach (\Yii::$app->params['sessionKeysForUser'] as $key) {
                    if (!empty(\Yii::$app->user->$key)) {
                        $session->set(\Yii::$app->params['usersKeyInSession'] . '.' . $key, \Yii::$app->user->$key);
                    }
                }*/
                
                $session->set(\Yii::$app->params['usersKeyInSession'], \Yii::$app->user->getUserDataForSession());
                
                /*if (!empty(\Yii::$app->user->id)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.id', \Yii::$app->user->id);
                }
                if (!empty(\Yii::$app->user->login)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.login', \Yii::$app->user->login);
                }
                if (!empty(\Yii::$app->user->name)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.name', \Yii::$app->user->name);
                }
                if (!empty(\Yii::$app->user->surname)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.surname', \Yii::$app->user->surname);
                }
                if (!empty(\Yii::$app->user->id_emails)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.id_emails', \Yii::$app->user->id_emails);
                }
                if (!empty(\Yii::$app->user->id_phones)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.id_phones', \Yii::$app->user->id_phones);
                }
                if (!empty(\Yii::$app->user->id_address)) {
                    $session->set(\Yii::$app->params['usersKeyInSession'] . '.id_address', \Yii::$app->user->id_address);
                }*/
                
                $session->close();
            }
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

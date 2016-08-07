<?php

namespace app\filters;

use yii\base\{ActionFilter, 
    ErrorException};
use app\traits\ExceptionsTrait;
use app\models\{UsersModel, 
    CurrencyModel};

/**
 * Заполняет объект \Yii::$app->shopUser данными сесии
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
            $session->open();
            if ($session->has(\Yii::$app->params['usersKeyInSession'])) {
                \Yii::configure(\Yii::$app->shopUser, $session->get(\Yii::$app->params['usersKeyInSession']));
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.currency')) {
                $currencyArray = $session->get(\Yii::$app->params['usersKeyInSession'] . '.currency');
            }
            $session->close();
            
            if (!empty($currencyArray)) {
                \Yii::$app->shopUser->currency = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_DB]);
                \Yii::configure(\Yii::$app->shopUser->currency, $currencyArray);
            }
            
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Сохраняет текущее состояние \Yii::$app->shopUser
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
            if (empty(\Yii::$app->params['nonAuthenticatedUserLogin'])) {
                throw new ErrorException('Не установлена переменная nonAuthenticatedUserLogin!');
            }
            
            $session = \Yii::$app->session;
            $session->open();
            if (!empty(\Yii::$app->shopUser->id_emails)) {
                $session->set(\Yii::$app->params['usersKeyInSession'], \Yii::$app->shopUser->getDataArray());
            }
            if (!empty(\Yii::$app->shopUser->currency)) {
                $session->set(\Yii::$app->params['usersKeyInSession'] . '.currency', \Yii::$app->shopUser->currency->getDataArray());
            }
            $session->close();
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

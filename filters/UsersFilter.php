<?php

namespace app\filters;

use yii\base\{ActionFilter, ErrorException};
use app\traits\ExceptionsTrait;
use app\models\{UsersModel, CurrencyModel};

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
            $session->open();
            if ($session->has(\Yii::$app->params['usersKeyInSession'])) {
                \Yii::configure(\Yii::$app->user, $session->get(\Yii::$app->params['usersKeyInSession']));
            }
            if ($session->has(\Yii::$app->params['usersKeyInSession'] . '.currency')) {
                $currencyArray = $session->get(\Yii::$app->params['usersKeyInSession'] . '.currency');
            }
            $session->close();
            
            if (!empty($currencyArray)) {
                \Yii::$app->user->currency = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_DB]);
                \Yii::configure(\Yii::$app->user->currency, $currencyArray);
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
            if (empty(\Yii::$app->params['nonAuthenticatedUserLogin'])) {
                throw new ErrorException('Не установлена переменная nonAuthenticatedUserLogin!');
            }
            
            $session = \Yii::$app->session;
            $session->open();
            if (\Yii::$app->user->login != \Yii::$app->params['nonAuthenticatedUserLogin']) {
                $session->set(\Yii::$app->params['usersKeyInSession'], \Yii::$app->user->getDataArray());
            }
            if (!empty(\Yii::$app->user->currency)) {
                $session->set(\Yii::$app->params['usersKeyInSession'] . '.currency', \Yii::$app->user->currency->getDataArray());
            }
            $session->close();
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

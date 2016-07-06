<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;

/**
 * Заполняет объект \Yii::$app->user данными сесии
 */
class UsersFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * @var array список полей, которые необходимо обновить для \Yii::$app->user
     */
    public static $_filedsToUser = ['id', 'login', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'];
    
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
                if (!\Yii::$app->cart->setProductsArray($session->get(\Yii::$app->params['cartKeyInSession'])) || empty(\Yii::$app->cart->getProductsArray())) {
                    throw new ErrorException('Ошибка при восстановлении данных из сессии!');
                }
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.user')) {
                    \Yii::$app->cart->user = $session->get(\Yii::$app->params['cartKeyInSession'] . '.user');
                    if (empty(\Yii::$app->cart->user) || !is_object(\Yii::$app->cart->user)) {
                        throw new ErrorException('Ошибка при восстановлении данных из сессии!');
                    }
                }
                $session->close();
                if (!\Yii::$app->cart->getShortData()) {
                    throw new ErrorException('Ошибка при восстановлении текущих данных корзины!');
                }
            }
            return parent::beforeAction($action);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Сохраняет текущее состояние корзины и данных заказа
     * @param $action выполняемое в данный момент действие
     * @param $result результирующая строка перед отправкой в браузер клиента
     * @return parent result
     */
    public function afterAction($action, $result)
    {
        try {
            if (empty(\Yii::$app->params['cartKeyInSession'])) {
                throw new ErrorException('Не установлена переменная cartKeyInSession!');
            }
            $session = \Yii::$app->session;
            $session->open();
            if (!empty(\Yii::$app->cart->getProductsArray())) {
                $session->set(\Yii::$app->params['cartKeyInSession'], \Yii::$app->cart->getProductsArray());
            }
            if (!empty(\Yii::$app->cart->user)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.user', \Yii::$app->cart->user);
            }
            $session->close();
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

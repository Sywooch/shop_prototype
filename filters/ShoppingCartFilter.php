<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;

/**
 * Заполняет объект \Yii::$app->cart данными сесии
 */
class ShoppingCartFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает из сессионного хранилища объект, хранящий данные корзины и заказа
     * @param $action выполняемое в данный момент действие
     * @return parent result
     */
    public function beforeAction($action)
    {
        try {
            if (empty(\Yii::$app->params['cartKeyInSession'])) {
                throw new ErrorException('Не установлена переменная cartKeyInSession!');
            }
            
            $session = \Yii::$app->session;
            if ($session->has(\Yii::$app->params['cartKeyInSession'])) {
                
                $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
                $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
                $addressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
                $phonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
                $deliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_FORM]);
                $paymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_FORM]);
                
                $session->open();
                
                \Yii::$app->cart->setProductsArray($session->get(\Yii::$app->params['cartKeyInSession']));
                
                \Yii::$app->cart->user = $usersModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.user')) {
                    \Yii::$app->cart->user->attributes = $session->get(\Yii::$app->params['cartKeyInSession'] . '.user');
                }
                
                \Yii::$app->cart->user->emails = $emailsModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.email')) {
                    \Yii::$app->cart->user->emails->attributes = $session->get(\Yii::$app->params['cartKeyInSession'] . '.email');
                }
                
                \Yii::$app->cart->user->phones = $phonesModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.phone')) {
                    \Yii::$app->cart->user->phones->attributes = $session->get(\Yii::$app->params['cartKeyInSession'] . '.phone');
                }
                
                \Yii::$app->cart->user->address = $addressModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.address')) {
                    \Yii::$app->cart->user->address->attributes = $session->get(\Yii::$app->params['cartKeyInSession'] . '.address');
                }
                
                \Yii::$app->cart->user->deliveries = $deliveriesModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.deliveries')) {
                    \Yii::$app->cart->user->deliveries->attributes = $session->get(\Yii::$app->params['cartKeyInSession'] . '.deliveries');
                }
                
                \Yii::$app->cart->user->payments = $paymentsModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.payments')) {
                    \Yii::$app->cart->user->payments->attributes = $session->get(\Yii::$app->params['cartKeyInSession'] . '.payments');
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
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.user', \Yii::$app->cart->user->getDataForSession());
            }
            
            if (!empty(\Yii::$app->cart->user->emails)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.email', \Yii::$app->cart->user->emails->getDataForSession());
            }
            
            if (!empty(\Yii::$app->cart->user->phones)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.phone', \Yii::$app->cart->user->phones->getDataForSession());
            }
            
            if (!empty(\Yii::$app->cart->user->address)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.address', \Yii::$app->cart->user->address->getDataForSession());
            }
            
            if (!empty(\Yii::$app->cart->user->deliveries)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.deliveries', \Yii::$app->cart->user->deliveries->getDataForSession());
            }
            
            if (!empty(\Yii::$app->cart->user->payments)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.payments', \Yii::$app->cart->user->payments->getDataForSession());
            }
            
            $session->close();
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

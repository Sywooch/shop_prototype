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
            if (empty(\Yii::$app->params['usersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная usersKeyInSession!');
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
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.name')) {
                    \Yii::$app->cart->user->name = $session->get(\Yii::$app->params['cartKeyInSession'] . '.user.name');
                }
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.surname')) {
                    \Yii::$app->cart->user->surname = $session->get(\Yii::$app->params['cartKeyInSession'] . '.user.surname');
                }
                
                \Yii::$app->cart->user->emails = $emailsModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.emails.email')) {
                    \Yii::$app->cart->user->emails->email = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.emails.email');
                }
                
                \Yii::$app->cart->user->phones = $phonesModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.phones.phone')) {
                    \Yii::$app->cart->user->phones->phone = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.phones.phone');
                }
                
                \Yii::$app->cart->user->address = $addressModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.address')) {
                    \Yii::$app->cart->user->address->address = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.address');
                }
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.city')) {
                    \Yii::$app->cart->user->address->city = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.city');
                }
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.postcode')) {
                    \Yii::$app->cart->user->address->postcode = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.postcode');
                }
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.country')) {
                    \Yii::$app->cart->user->address->country = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.country');
                }
                
                \Yii::$app->cart->user->deliveries = $deliveriesModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.deliveries.id')) {
                    \Yii::$app->cart->user->deliveries->id = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.deliveries.id');
                }
                
                \Yii::$app->cart->user->payments = $paymentsModel;
                if ($session->has(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.payments.id')) {
                    \Yii::$app->cart->user->payments->id = $session->get(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.payments.id');
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
            
            if (!empty(\Yii::$app->cart->user->name)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.name', \Yii::$app->cart->user->name);
            }
            if (!empty(\Yii::$app->cart->user->surname)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.surname', \Yii::$app->cart->user->surname);
            }
            
            if (!empty(\Yii::$app->cart->user->emails->email)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.emails.email', \Yii::$app->cart->user->emails->email);
            }
            
            if (!empty(\Yii::$app->cart->user->phones->phone)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.phones.phone', \Yii::$app->cart->user->phones->phone);
            }
            
            if (!empty(\Yii::$app->cart->user->address->address)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.address', \Yii::$app->cart->user->address->address);
            }
            if (!empty(\Yii::$app->cart->user->address->city)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.city', \Yii::$app->cart->user->address->city);
            }
            if (!empty(\Yii::$app->cart->user->address->postcode)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.postcode', \Yii::$app->cart->user->address->postcode);
            }
            if (!empty(\Yii::$app->cart->user->address->country)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.address.country', \Yii::$app->cart->user->address->country);
            }
            
            if (!empty(\Yii::$app->cart->user->deliveries->id)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.deliveries.id', \Yii::$app->cart->user->deliveries->id);
            }
            
            if (!empty(\Yii::$app->cart->user->payments->id)) {
                $session->set(\Yii::$app->params['cartKeyInSession'] . '.' .\Yii::$app->params['usersKeyInSession'] . '.payments.id', \Yii::$app->cart->user->payments->id);
            }
            
            $session->close();
            
            return parent::afterAction($action, $result);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

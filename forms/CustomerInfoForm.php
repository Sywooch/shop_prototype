<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные покупателя при оформлении заказа
 */
class CustomerInfoForm extends AbstractBaseForm
{
    /**
     * Сценарий получения данных для оформления заказа
     */
    const CHECKOUT = 'checkout';
    
    /**
     * @var string имя покупателя
     */
    public $name;
    /**
     * @var string фамилия покупателя
     */
    public $surname;
    /**
     * @var string email покупателя
     */
    public $email;
    /**
     * @var string номер телефона покупателя
     */
    public $phone;
    /**
     * @var string адрес доставки
     */
    public $address;
    /**
     * @var string город доставки
     */
    public $city;
    /**
     * @var string страна доставки
     */
    public $country;
    /**
     * @var string почтовый индекс
     */
    public $postcode;
    /**
     * @var int ID способа доставки
     */
    public $delivery;
    /**
     * @var int ID способа оплаты
     */
    public $payment;
    
    public function scenarios()
    {
        return [
            self::CHECKOUT=>['name', 'surname', 'email', 'phone', 'address', 'city', 'country', 'postcode', 'delivery', 'payment']
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'phone', 'address', 'city', 'country', 'postcode', 'delivery', 'payment'], 'required', 'on'=>self::CHECKOUT]
        ];
    }
}

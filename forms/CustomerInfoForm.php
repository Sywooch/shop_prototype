<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{PasswordIdenticRegValidator,
    UserEmailExistsRegValidator,
    StripTagsValidator};

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
     * @var int почтовый индекс
     */
    public $postcode;
    /**
     * @var int ID способа доставки
     */
    public $id_delivery;
    /**
     * @var int ID способа оплаты
     */
    public $id_payment;
    /**
     * @var int нужно ли создавать аккаунт
     */
    public $create;
    /**
     * @var string пароль
     */
    public $password;
    /**
     * @var string подтверждение пароля
     */
    public $password2;
    /**
     * @var int нужно ли обновить данные
     */
    public $change;
    
    public function scenarios()
    {
        return [
            self::CHECKOUT=>['name', 'surname', 'email', 'phone', 'address', 'city', 'country', 'postcode', 'id_delivery', 'id_payment', 'create', 'password', 'password2', 'change']
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'phone', 'address', 'city', 'country', 'postcode', 'id_delivery', 'id_payment', 'create', 'password', 'password2', 'change'], StripTagsValidator::class],
            [['name', 'surname', 'email', 'phone', 'address', 'city', 'country', 'postcode', 'id_delivery', 'id_payment'], 'required', 'on'=>self::CHECKOUT],
            [['postcode', 'id_delivery', 'id_payment', 'create', 'change'], 'integer'],
            [['name', 'surname', 'email', 'phone', 'address', 'city', 'country', 'password', 'password2'], 'string'],
            [['name', 'surname'], 'match', 'pattern'=>'#^(?:[A-ZА-Я]{1}[a-zа-я\s]+-?)+$#u'],
            [['email'], 'email'],
            [['phone'], 'match', 'pattern'=>'#^[+()0-9\s-]+$#u'],
            [['city', 'country'], 'match', 'pattern'=>'#^[a-zа-я\s0-9-]+$#ui'],
            [['email'], UserEmailExistsRegValidator::class, 'on'=>self::CHECKOUT, 'when'=>function($model) {
                return !empty($model->create);
            }],
            [['password', 'password2'], 'required', 'on'=>self::CHECKOUT, 'when'=>function($model) {
                return !empty($model->create);
            }],
            [['password2'], PasswordIdenticRegValidator::class, 'on'=>self::CHECKOUT, 'when'=>function($model) {
                return !empty($model->create);
            }],
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы для изменения заказа
 */
class AdminChangeOrderForm extends AbstractBaseForm
{
    /**
     * Сценарий сохранения изменений в заказе
     */
    const SAVE = 'save';
    
    /**
     * @var int Id заказа
     */
    public $id;
    /**
     * @var string имя
     */
    public $name;
    /**
     * @var string фамилия
     */
    public $surname;
    /**
     * @var string телефон
     */
    public $phone;
    /**
     * @var string адрес
     */
    public $address;
    /**
     * @var string город
     */
    public $city;
    /**
     * @var string страна
     */
    public $country;
    /**
     * @var string почтовый код
     */
    public $postcode;
    /**
     * @var int количество
     */
    public $quantity;
    /**
     * @var int цвет
     */
    public $color;
    /**
     * @var int размер
     */
    public $size;
    /**
     * @var int доставка
     */
    public $delivery;
    /**
     * @var int оплата
     */
    public $payment;
    /**
     * @var string статус
     */
    public $status;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'quantity', 'color', 'size', 'delivery', 'payment', 'status'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'quantity', 'color', 'size', 'delivery', 'payment', 'status'], 'required', 'on'=>self::SAVE],
        ];
    }
}

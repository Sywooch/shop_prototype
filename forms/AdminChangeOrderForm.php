<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет данные формы для изменения заказа
 */
class AdminChangeOrderForm extends AbstractBaseForm
{
    /**
     * Сценарий запроса формы для редактирования заказа
     */
    const GET = 'get';
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
    public $id_color;
    /**
     * @var int размер
     */
    public $id_size;
    /**
     * @var int доставка
     */
    public $id_delivery;
    /**
     * @var int оплата
     */
    public $id_payment;
    /**
     * @var string статус
     */
    public $status;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'status'],
            self::GET=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'status'], StripTagsValidator::class],
            [['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment', 'status'], 'required', 'on'=>self::SAVE],
            [['id'], 'required', 'on'=>self::GET],
        ];
    }
}

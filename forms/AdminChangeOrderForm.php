<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{OrderStatusExistsValidator,
    StripTagsValidator};

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
            [['name', 'surname', 'phone', 'address', 'city', 'country', 'status'], 'string'],
            [['id', 'postcode', 'quantity', 'id_color', 'id_size', 'id_delivery', 'id_payment'], 'integer'],
            [['name', 'surname'], 'match', 'pattern'=>'#^[A-ZА-Я]{1}[a-zа-я]+-?[A-ZА-Я]?[a-zа-я]*$#u'],
            [['phone'], 'match', 'pattern'=>'#^[+()0-9\s-]+$#u'],
            [['city', 'country'], 'match', 'pattern'=>'#^[A-ZА-Яa-zа-я\s0-9]+$#u'],
            [['status'], OrderStatusExistsValidator::class],
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет обновляемые данные пользователя
 */
class UserUpdateForm extends AbstractBaseForm
{
    /**
     * Сценарий обновления данных пользователя
     */
    const UPDATE = 'update';
    /**
     * Сценарий обновления данных пользователя через админ раздел
     */
    const ADMIN_UPDATE = 'admin_update';
    
    /**
     * @var int id покупателя
     */
    public $id;
    /**
     * @var string имя покупателя
     */
    public $name;
    /**
     * @var string фамилия покупателя
     */
    public $surname;
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
    
    public function scenarios()
    {
        return [
            self::UPDATE=>['name', 'surname', 'phone', 'address', 'city', 'country', 'postcode'],
            self::ADMIN_UPDATE=>['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode']
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode'], StripTagsValidator::class],
            [['name', 'surname', 'phone', 'address', 'city', 'country', 'postcode'], 'required', 'on'=>self::UPDATE],
            [['id', 'name', 'surname', 'phone', 'address', 'city', 'country', 'postcode'], 'required', 'on'=>self::ADMIN_UPDATE],
            [['id', 'postcode'], 'integer'],
            [['name', 'surname', 'phone', 'address', 'city', 'country'], 'string'],
            [['name', 'surname'], 'match', 'pattern'=>'#^(?:[A-ZА-Я]{1}[a-zа-я]+-?\s?)+$#u'],
            [['phone'], 'match', 'pattern'=>'#^[+()0-9\s-]+$#u'],
            [['city', 'country'], 'match', 'pattern'=>'#^[a-zа-я\s0-9-]+$#ui'],
        ];
    }
}

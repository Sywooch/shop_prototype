<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
     * @var string почтовый индекс
     */
    public $postcode;
    
    public function scenarios()
    {
        return [
            self::UPDATE=>['name', 'surname', 'phone', 'address', 'city', 'country', 'postcode']
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'surname', 'phone', 'address', 'city', 'country', 'postcode'], 'required', 'on'=>self::UPDATE],
        ];
    }
}

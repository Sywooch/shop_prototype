<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateDeliveryNameExistsValidator,
    DeleteDeliveryOrdersExistsValidator};

/**
 * Представляет данные формы редактирования способов доставки
 */
class DeliveriesForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления способа доставки
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания способа доставки
     */
    const CREATE = 'create';
    /**
     * Сценарий запроса формы для редактирования
     */
    const GET = 'get';
    /**
     * Сценарий редактирования способа доставки
     */
    const EDIT = 'edit';
    
    /**
     * @var int ID способа доставки
     */
    public $id;
    /**
     * @var string название способа доставки
     */
    public $name;
    /**
     * @var string описание способа доставки
     */
    public $description;
    /**
     * @var float стоимость способа доставки
     */
    public $price;
    /**
     * @var int активна или нет доставка
     */
    public $active;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'description', 'price', 'active'],
            self::GET=>['id'],
            self::EDIT=>['id', 'name', 'description', 'price', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteDeliveryOrdersExistsValidator::class, 'on'=>self::DELETE],
            [['name', 'description', 'price'], 'required', 'on'=>self::CREATE],
            [['name'], CreateDeliveryNameExistsValidator::class, 'on'=>self::CREATE],
            [['id'], 'required', 'on'=>self::GET],
            [['id', 'name', 'description', 'price'], 'required', 'on'=>self::EDIT],
        ];
    }
}

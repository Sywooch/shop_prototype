<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'description', 'price'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['name', 'description', 'price'], 'required', 'on'=>self::CREATE],
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы для изменения статуса заказа
 */
class OrderStatusForm extends AbstractBaseForm
{
    /**
     * Сценарий сохранения изменений в статусе заказа
     */
    const SAVE = 'save';
    
    /**
     * @var int Id заказа
     */
    public $id;
    /**
     * @var int Id статуса
     */
    public $status;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['id', 'status'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'status'], 'required', 'on'=>self::SAVE],
        ];
    }
}

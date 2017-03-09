<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreatePaymentNameExistsValidator,
    DeletePaymentOrdersExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы редактирования способов оплаты
 */
class PaymentsForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления способа оплаты
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания способа оплаты
     */
    const CREATE = 'create';
    /**
     * Сценарий запроса формы для редактирования
     */
    const GET = 'get';
    /**
     * Сценарий редактирования способа оплаты
     */
    const EDIT = 'edit';
    
    /**
     * @var int ID способа оплаты
     */
    public $id;
    /**
     * @var string название способа оплаты
     */
    public $name;
    /**
     * @var string описание способа оплаты
     */
    public $description;
    /**
     * @var int активен или нет способ оплаты
     */
    public $active;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'description', 'active'],
            self::GET=>['id'],
            self::EDIT=>['id', 'name', 'description', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'name', 'description', 'active'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['name', 'description'], 'required', 'on'=>self::CREATE],
            [['id'], 'required', 'on'=>self::GET],
            [['id', 'name', 'description'], 'required', 'on'=>self::EDIT],
            [['id', 'active'], 'integer'],
            [['name', 'description'], 'string'],
            [['name', 'description'], 'match', 'pattern'=>'#^[a-zа-я\s,.!0-9]+$#iu'],
            [['id'], DeletePaymentOrdersExistsValidator::class, 'on'=>self::DELETE],
            [['name'], CreatePaymentNameExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

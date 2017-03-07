<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateMailingNameExistsValidator,
    DeleteMailingUsersExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы редактирования способов оплаты
 */
class AdminMailingForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления подписки
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания подписки
     */
    const CREATE = 'create';
    /**
     * Сценарий запроса формы для редактирования
     */
    const GET = 'get';
    /**
     * Сценарий редактирования подписки
     */
    const EDIT = 'edit';
    
    /**
     * @var int ID подписки
     */
    public $id;
    /**
     * @var string название подписки
     */
    public $name;
    /**
     * @var string описание подписки
     */
    public $description;
    /**
     * @var int активна или нет подписка
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
            [['id'], 'required', 'on'=>self::GET],
            [['name', 'description'], 'required', 'on'=>self::CREATE],
            [['id', 'name', 'description'], 'required', 'on'=>self::EDIT],
            [['id', 'active'], 'integer'],
            [['name', 'description'], 'string'],
            [['id'], DeleteMailingUsersExistsValidator::class, 'on'=>self::DELETE],
            [['name'], CreateMailingNameExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

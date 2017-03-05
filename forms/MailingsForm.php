<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateMailingNameExistsValidator,
    DeleteMailingUsersExistsValidator};

/**
 * Представляет данные формы редактирования способов оплаты
 */
class MailingsForm extends AbstractBaseForm
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
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteMailingUsersExistsValidator::class, 'on'=>self::DELETE],
            [['name', 'description'], 'required', 'on'=>self::CREATE],
            [['name'], CreateMailingNameExistsValidator::class, 'on'=>self::CREATE],
            [['id'], 'required', 'on'=>self::GET],
            [['id', 'name', 'description'], 'required', 'on'=>self::EDIT],
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет данные формы добавления коментария
 */
class CommentForm extends AbstractBaseForm
{
    /**
     * Сценарий добавления коментария
     */
    const SAVE = 'save';
    /**
     * Сценарий запроса формы для редактирования
     */
    const GET = 'get';
    /**
     * Сценарий удаления коментария
     */
    const DELETE = 'delete';
    /**
     * Сценарий редактирования коментария
     */
    const EDIT = 'edit';
    
    /**
     * @var int ID комментария
     */
    public $id;
    /**
     * @var string текст комментария
     */
    public $text;
    /**
     * @var string имя автора
     */
    public $name;
    /**
     * @var string email имя автора
     */
    public $email;
    /**
     * @var int ID товара, которому предназначен комментарий
     */
    public $id_product;
    /**
     * @var int флаг доступности комментария
     */
    public $active;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['text', 'name', 'email', 'id_product'],
            self::GET=>['id'],
            self::DELETE=>['id'],
            self::EDIT=>['id', 'text', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'text', 'name', 'email', 'id_product', 'active'], StripTagsValidator::class],
            [['text', 'name', 'email', 'id_product'], 'required', 'on'=>self::SAVE],
            [['id'], 'required', 'on'=>self::GET],
            [['id'], 'required', 'on'=>self::DELETE],
            [['id', 'text'], 'required', 'on'=>self::EDIT],
            [['id', 'id_product', 'active'], 'integer'],
            [['text', 'name', 'email'], 'string'],
            [['email'], 'email'],
        ];
    }
}

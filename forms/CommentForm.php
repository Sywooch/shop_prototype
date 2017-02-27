<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
    
    public function scenarios()
    {
        return [
            self::SAVE=>['text', 'name', 'email', 'id_product'],
            self::GET=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['text', 'name', 'email', 'id_product'], 'required', 'on'=>self::SAVE],
            [['email'], 'email', 'on'=>self::SAVE],
            [['id'], 'required', 'on'=>self::GET]
        ];
    }
}

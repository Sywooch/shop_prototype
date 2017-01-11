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
        ];
    }
    
    public function rules()
    {
        return [
            [['text', 'name', 'email', 'id_product'], 'required', 'enableClientValidation'=>true, 'on'=>self::SAVE],
            [['email'], 'email', 'enableClientValidation'=>true, 'on'=>self::SAVE]
        ];
    }
}

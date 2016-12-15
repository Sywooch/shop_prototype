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
    const GET = 'get';
    
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
     * @var int ID товара, которому предназначен
     */
    public $id_product;
    
    public function scenarios()
    {
        return [
            self::GET=>['text', 'name', 'email', 'id_product']
        ];
    }
    
    public function rules()
    {
        return [
            [['text', 'name', 'email', 'id_product'], 'required', 'on'=>self::GET],
            [['email'], 'email', 'on'=>self::GET]
        ];
    }
}

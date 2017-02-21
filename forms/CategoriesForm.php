<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\DeleteCategorySubcategoryExistsValidator;

/**
 * Представляет данные формы редактирования категорий
 */
class CategoriesForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления категории
     */
    const DELETE = 'delete';
    
    /**
     * @var int ID
     */
    public $id;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteCategorySubcategoryExistsValidator::class, 'on'=>self::DELETE],
        ];
    }
}

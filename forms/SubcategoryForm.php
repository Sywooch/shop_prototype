<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\DeleteSubcategoryProductsExistsValidator;

/**
 * Представляет данные формы редактирования категорий
 */
class SubcategoryForm extends AbstractBaseForm
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
            [['id'], DeleteSubcategoryProductsExistsValidator::class, 'on'=>self::DELETE],
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateCategoryNameExistsValidator,
    CreateCategorySeocodeExistsValidator,
    DeleteCategorySubcategoryExistsValidator,
    StripTagsValidator};

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
     * Сценарий создания категории
     */
    const CREATE = 'create';
    /**
     * Сценарий редактирования категории
     */
    const EDIT = 'edit';
    
    /**
     * @var int ID
     */
    public $id;
    /**
     * @var string имя категории
     */
    public $name;
    /**
     * @var string seocode
     */
    public $seocode;
    /**
     * @var bool
     */
    public $active;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'seocode', 'active'],
            self::EDIT=>['id', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'seocode'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteCategorySubcategoryExistsValidator::class, 'on'=>self::DELETE],
            [['name', 'seocode'], 'required', 'on'=>self::CREATE],
            [['name'], CreateCategoryNameExistsValidator::class, 'on'=>self::CREATE],
            [['seocode'], CreateCategorySeocodeExistsValidator::class, 'on'=>self::CREATE],
            [['id'], 'required', 'on'=>self::EDIT],
        ];
    }
}

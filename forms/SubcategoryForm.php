<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateSubcategoryNameExistsValidator,
    CreateSubcategorySeocodeExistsValidator,
    DeleteSubcategoryProductsExistsValidator,
    StripTagsValidator};

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
     * Сценарий создания подкатегории
     */
    const CREATE = 'create';
    
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
     * @var id подкатегории
     */
    public $id_category;
    /**
     * @var bool
     */
    public $active;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'seocode', 'id_category', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['name', 'seocode'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteSubcategoryProductsExistsValidator::class, 'on'=>self::DELETE],
            [['name', 'seocode', 'id_category'], 'required', 'on'=>self::CREATE],
            [['name'], CreateSubcategoryNameExistsValidator::class, 'on'=>self::CREATE],
            [['seocode'], CreateSubcategorySeocodeExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

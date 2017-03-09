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
     * Сценарий редактирования подкатегории
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
     * @var id категории
     */
    public $id_category;
    /**
     * @var int
     */
    public $active;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['name', 'seocode', 'id_category', 'active'],
            self::EDIT=>['id', 'active'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'name', 'seocode', 'id_category', 'active'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], 'required', 'on'=>self::EDIT],
            [['name', 'seocode', 'id_category'], 'required', 'on'=>self::CREATE],
            [['id', 'id_category', 'active'], 'integer'],
            [['name', 'seocode'], 'string'],
            [['name'], 'match', 'pattern'=>'#^[a-zа-я\s]+$#ui'],
            [['seocode'], 'match', 'pattern'=>'#^[a-z-]+$#u'],
            [['id'], DeleteSubcategoryProductsExistsValidator::class, 'on'=>self::DELETE],
            [['name'], CreateSubcategoryNameExistsValidator::class, 'on'=>self::CREATE],
            [['seocode'], CreateSubcategorySeocodeExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

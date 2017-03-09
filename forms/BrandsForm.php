<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateBrandBrandExistsValidator,
    DeleteBrandProductsExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы редактирования брендов
 */
class BrandsForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления бренда
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания бренда
     */
    const CREATE = 'create';
    
    /**
     * @var int ID
     */
    public $id;
    /**
     * @var string имя бренда
     */
    public $brand;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['brand'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'brand'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['brand'], 'required', 'on'=>self::CREATE],
            [['id'], 'integer'],
            [['brand'], 'string'],
            [['brand'], 'match', 'pattern'=>'#[a-z-0-9\s]#iu'],
            [['id'], DeleteBrandProductsExistsValidator::class, 'on'=>self::DELETE],
            [['brand'], CreateBrandBrandExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

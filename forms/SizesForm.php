<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateSizeSizeExistsValidator,
    DeleteSizeProductsExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы редактирования размеров
 */
class SizesForm extends AbstractBaseForm
{
    /**
     * Сценарий удаления размера
     */
    const DELETE = 'delete';
    /**
     * Сценарий создания размера
     */
    const CREATE = 'create';
    
    /**
     * @var int ID
     */
    public $id;
    /**
     * @var float размеры
     */
    public $size;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['size'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id', 'size'], StripTagsValidator::class],
            [['id'], 'required', 'on'=>self::DELETE],
            [['size'], 'required', 'on'=>self::CREATE],
            [['id'], 'integer'],
            [['size'], 'double'],
            [['id'], DeleteSizeProductsExistsValidator::class, 'on'=>self::DELETE],
            [['size'], CreateSizeSizeExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

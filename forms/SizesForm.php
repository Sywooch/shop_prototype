<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateSizeSizeExistsValidator,
    DeleteSizeProductsExistsValidator};

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
     * @var string название цвета
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
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteSizeProductsExistsValidator::class, 'on'=>self::DELETE],
            [['size'], 'required', 'on'=>self::CREATE],
            [['size'], CreateSizeSizeExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

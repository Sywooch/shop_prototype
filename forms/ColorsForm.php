<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{CreateColorColorExistsValidator,
    DeleteColorProductsExistsValidator};

/**
 * Представляет данные формы редактирования цветов
 */
class ColorsForm extends AbstractBaseForm
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
     * @var string название цвета
     */
    public $color;
    
    public function scenarios()
    {
        return [
            self::DELETE=>['id'],
            self::CREATE=>['color'],
        ];
    }
    
    public function rules()
    {
        return [
            [['id'], 'required', 'on'=>self::DELETE],
            [['id'], DeleteColorProductsExistsValidator::class, 'on'=>self::DELETE],
            [['color'], 'required', 'on'=>self::CREATE],
            [['color'], CreateColorColorExistsValidator::class, 'on'=>self::CREATE],
        ];
    }
}

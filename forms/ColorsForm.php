<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

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
            //[['id'], DeleteColorProductsExistsValidator::class, 'on'=>self::DELETE],
        ];
    }
}

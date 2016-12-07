<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class FiltersForm extends AbstractBaseForm
{
    /**
     * Сценарий сохранения настроек фильтров
    */
    const SAVE = 'save';
    
    public $sortingField = '';
    public $sortingType = '';
    public $colors = [];
    public $sizes = [];
    public $brands = [];
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'colors', 'sizes', 'brands'],
        ];
    }
}

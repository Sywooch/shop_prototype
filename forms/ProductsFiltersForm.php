<?php

namespace app\forms;

use yii\base\{ErrorException,
    Model};
use app\forms\{AbstractBaseForm,
    FormInterface};
use app\models\ProductsModel;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class ProductsFiltersForm extends AbstractFormModel implements FormInterface
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
    
    /**
     * Обнуляет значение всех свойств, очищая фильтры
     */
    public function clean()
    {
        try {
            $this->sortingField = '';
            $this->sortingType = '';
            $this->colors = [];
            $this->sizes = [];
            $this->brands = [];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

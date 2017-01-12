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
     * Сценарий сохранения значений фильтров
     */
    const SAVE = 'save';
    /**
     * Сценарий обнуления фильтров
     */
    const CLEAN = 'clean';
    
    /**
     * @var string имя столбца, покоторому будут отсортированы результаты
     */
    public $sortingField;
    /**
     * @var string тип сортировки
     */
    public $sortingType;
    /**
     * @var array массив ID цветов для сортировки
     */
    public $colors;
    /**
     * @var array массив ID размеров для сортировки
     */
    public $sizes;
    /**
     * @var array массив ID брендов для сортировки
     */
    public $brands;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    /**
     * @var string seocode категории
     */
    public $category;
    /**
     * @var string seocode подкатегории
     */
    public $subcategory;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'colors', 'sizes', 'brands', 'url', 'category', 'subcategory'],
            self::CLEAN=>['url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['url'], 'required', 'enableClientValidation'=>true],
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{ActiveStatusExistsValidator,
    ActiveStatusTypeValidator,
    IntInArrayValidator,
    SortingFieldExistsValidator,
    SortingTypeExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы фильтров для списка заказов
 */
class AdminProductsFiltersForm extends AbstractBaseForm
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
     * @var string имя столбца, покоторому будут отсортированы результаты (date, price, views)
     */
    public $sortingField;
    /**
     * @var int тип сортировки
     */
    public $sortingType;
    /**
     * @var array ID цветов для сортировки
     */
    public $colors;
    /**
     * @var array ID размеров для сортировки
     */
    public $sizes;
    /**
     * @var array ID брендов для сортировки
     */
    public $brands;
    /**
     * @var int ID категории
     */
    public $category;
    /**
     * @var array массив ID подкатегорий
     */
    public $subcategory;
    /**
     * @var int
     */
    public $active;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'colors', 'sizes', 'brands', 'category', 'subcategory', 'active', 'url'],
            self::CLEAN=>['url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'colors', 'sizes', 'brands', 'category', 'subcategory', 'active', 'url'], StripTagsValidator::class],
            [['url'], 'required'],
            [['sortingField', 'url'], 'string'],
            [['sortingType', 'category', 'subcategory', 'active'], 'integer'],
            [['sortingField'], SortingFieldExistsValidator::class],
            [['sortingType'], SortingTypeExistsValidator::class],
            [['colors', 'sizes', 'brands'], IntInArrayValidator::class],
            [['active'], ActiveStatusExistsValidator::class],
            [['active'], ActiveStatusTypeValidator::class, 'on'=>self::SAVE],
            [['url'], 'match', 'pattern'=>'#^/[a-z]+/?[a-z-]*-?[0-9]*$#u'],
        ];
    }
}

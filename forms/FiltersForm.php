<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{IntInArrayValidator,
    ModSortingFieldExistsValidator,
    SortingTypeExistsValidator,
    StripTagsValidator};

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
     * @var int тип сортировки
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
            [['sortingField', 'sortingType', 'colors', 'sizes', 'brands', 'url', 'category', 'subcategory'], StripTagsValidator::class],
            [['url'], 'required'],
            [['sortingType'], 'integer'],
            [['sortingField', 'url', 'category', 'subcategory'], 'string'],
            [['colors', 'sizes', 'brands'], IntInArrayValidator::class],
            [['url'], 'match', 'pattern'=>'#^/[a-z-0-9/?=%]+$#u'],
            [['category', 'subcategory'], 'match', 'pattern'=>'#^[a-z-]+$#u'],
            [['sortingField'], ModSortingFieldExistsValidator::class],
            [['sortingType'], SortingTypeExistsValidator::class],
        ];
    }
    
    public function fields()
    {
        return [
            'sortingField'=>function() {
                $field = explode(' ', $this->sortingField)[0];
                return $field;
            },
            'sortingType'=>function() {
                $type = explode(' ', $this->sortingField)[1];
                return ($type === 'ascending') ? SORT_ASC : SORT_DESC;
            },
            'colors'=>function() {
                return !empty($this->colors) ? $this->colors : [];
            },
            'sizes'=>function() {
                return  !empty($this->sizes) ? $this->sizes : [];
            },
            'brands'=>function() {
                return !empty($this->brands) ? $this->brands : [];
            },
            'url',
            'category',
            'subcategory',
        ];
    }
}

<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\StripTagsValidator;

/**
 * Представляет данные формы фильтров для списка заказов
 */
class OrdersFiltersForm extends AbstractBaseForm
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
     * @var string статус заказа
     */
    public $status;
    /**
     * @var int Unix Timestamp
     */
    public $dateFrom;
    /**
     * @var int Unix Timestamp
     */
    public $dateTo;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'status', 'url', 'dateFrom', 'dateTo'],
            self::CLEAN=>['url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'status', 'dateFrom', 'dateTo', 'url'], StripTagsValidator::class],
            [['url'], 'required'],
        ];
    }
}

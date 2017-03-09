<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{ActiveStatusTypeValidator,
    SortingFieldsUsersExistsValidator,
    SortingTypeExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы фильтров для списка пользователей
 */
class UsersFiltersForm extends AbstractBaseForm
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
     * @var int статус заказов
     */
    public $ordersStatus;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'ordersStatus', 'url'],
            self::CLEAN=>['url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'ordersStatus', 'url'], StripTagsValidator::class],
            [['url'], 'required'],
            [['sortingType', 'ordersStatus'], 'integer'],
            [['sortingField', 'url'], 'string'],
            [['url'], 'match', 'pattern'=>'#^/[a-z-0-9]+$#u'],
            [['sortingField'], SortingFieldsUsersExistsValidator::class],
            [['sortingType'], SortingTypeExistsValidator::class],
            [['ordersStatus'], ActiveStatusTypeValidator::class, 'on'=>self::SAVE]
        ];
    }
}

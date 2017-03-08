<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\{ActiveStatusExistsValidator,
    ActiveStatusTypeValidator,
    SortingFieldExistsValidator,
    SortingTypeExistsValidator,
    StripTagsValidator};

/**
 * Представляет данные формы фильтров для списка комментариев
 */
class AdminCommentsFiltersForm extends AbstractBaseForm
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
     * @var string статус комментария
     */
    public $activeStatus;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'activeStatus', 'url'],
            self::CLEAN=>['url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['sortingField', 'sortingType', 'activeStatus', 'url'], StripTagsValidator::class],
            [['url'], 'required'],
            [['sortingField', 'activeStatus', 'url'], 'string'],
            [['sortingType'], 'integer'],
            [['url'], 'match', 'pattern'=>'#^/[a-z-]+/?[a-z-]*-?[0-9]*$#i'],
            [['sortingField'], SortingFieldExistsValidator::class],
            [['sortingType'], SortingTypeExistsValidator::class],
            [['activeStatus'], ActiveStatusExistsValidator::class],
            [['activeStatus'], ActiveStatusTypeValidator::class, 'on'=>self::SAVE],
        ];
    }
}

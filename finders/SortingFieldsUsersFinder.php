<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные поля сортировки
 */
class SortingFieldsUsersFinder extends AbstractBaseFinder
{
    /**
     * @var array
     */
    private $storage = null;
    
    /**
     * Возвращает данные
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                $this->storage = [
                    'id'=>\Yii::t('base', 'Sorting by id'),
                    'name'=>\Yii::t('base', 'Sorting by name'),
                    'surname'=>\Yii::t('base', 'Sorting by surname'),
                    'orders'=>\Yii::t('base', 'Sorting by orders')
                ];
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

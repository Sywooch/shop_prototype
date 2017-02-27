<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные поля сортировки
 */
class SortingFieldsCommentsFinder extends AbstractBaseFinder
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
                    'date'=>\Yii::t('base', 'Sorting by date'),
                ];
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

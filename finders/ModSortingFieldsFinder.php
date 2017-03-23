<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные поля сортировки
 */
class ModSortingFieldsFinder extends AbstractBaseFinder
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
                    'date descending'=>\Yii::t('base', 'by date &#8595;'),
                    'date ascending'=>\Yii::t('base', 'by date &#8593;'),
                    'price descending'=>\Yii::t('base', 'by price &#8595;'),
                    'price ascending'=>\Yii::t('base', 'by price &#8593;')
                ];
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

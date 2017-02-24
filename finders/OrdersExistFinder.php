<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает доступные поля сортировки по доступности
 */
class OrdersExistFinder extends AbstractBaseFinder
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
                    0=>\Yii::$app->params['formFiller'],
                    1=>\Yii::t('base', 'Has orders'),
                    2=>\Yii::t('base', 'Had no orders')
                ];
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

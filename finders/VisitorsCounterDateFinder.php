<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\VisitorsCounterModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает VisitorsCounterModel из СУБД
 */
class VisitorsCounterDateFinder extends AbstractBaseFinder
{
    /**
     * @var int date
     */
    public $date;
    /**
     * @var VisitorsCounterModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->date)) {
                throw new ErrorException($this->emptyError('date'));
            }
            
            if (empty($this->storage)) {
                $query = VisitorsCounterModel::find();
                $query->select(['[[visitors_counter.date]]', '[[visitors_counter.counter]]']);
                $query->where(['[[visitors_counter.date]]'=>$this->date]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

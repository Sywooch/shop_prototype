<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\VisitorsCounterModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает VisitorsCounterModel из СУБД
 */
class VisitorsCounterFinder extends AbstractBaseFinder
{
    /**
     * @var array VisitorsCounterModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                $query = VisitorsCounterModel::find();
                $query->select(['[[visitors_counter.date]]', '[[visitors_counter.counter]]']);
                $query->orderBy(['[[visitors_counter.date]]'=>SORT_DESC]);
                $query->limit(\Yii::$app->params['visitorsLimit']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use yii\sphinx\{MatchExpression,
    Query};

/**
 * Возвращает коллекцию товаров из sphinx
 */
class SphinxFinder extends AbstractBaseFinder
{
    /**
     * @var string искомая фраза
     */
    public $search;
    /**
     * @var массив загруженных данных
     */
    private $storage = null;
    
    /**
     * Возвращает данные из sphinx
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if (empty($this->search)) {
                    throw new ErrorException($this->emptyError('search'));
                }
                
                $query = new Query();
                $query->select(['id']);
                $query->from('{{shop}}');
                $query->match(new MatchExpression('[[@* :search]]', ['search'=>$this->search]));
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

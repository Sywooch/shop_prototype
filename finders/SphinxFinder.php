<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;
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
    
    public function rules()
    {
        return [
            [['search'], 'required']
        ];
    }
    
    /**
     * Возвращает данные из sphinx
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                
                $query = new Query();
                $query->select(['id']);
                $query->from('{{shop}}');
                $query->match(new MatchExpression('[[@* :search]]', ['search'=>$this->search]));
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

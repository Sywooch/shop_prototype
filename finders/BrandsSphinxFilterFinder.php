<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\BrandsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию товаров из СУБД
 */
class BrandsSphinxFilterFinder extends AbstractBaseFinder
{
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    public $found;
    
    public function rules()
    {
        return [
            [['found'], 'required']
        ];
    }
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            
            if ($this->collection->isEmpty()) {
                
                $query = BrandsModel::find();
                $query->select(['[[brands.id]]', '[[brands.brand]]']);
                $query->distinct();
                $query->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
                $query->where(['[[products.active]]'=>true]);
                $query->andWhere(['[[products.id]]'=>$this->found]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

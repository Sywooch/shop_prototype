<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ColorsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию товаров из СУБД
 */
class ColorsSphinxFilterFinder extends AbstractBaseFinder
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
                
                $query = ColorsModel::find();
                $query->select(['[[colors.id]]', '[[colors.color]]']);
                $query->distinct();
                $query->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                $query->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
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

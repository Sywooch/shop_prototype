<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию товаров из СУБД
 */
class ProductsSphinxFinder extends AbstractBaseFinder
{
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    public $found;
    /**
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    public $page;
    
    public function rules()
    {
        return [
            [['found'], 'required'],
            [['found', 'page'], 'safe']
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
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.active]]'=>true]);
                $query->andWhere(['[[products.id]]'=>$this->found]);
                
                $this->collection->pagination->pageSize = \Yii::$app->params['limit'];
                $this->collection->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->collection->pagination->setTotalCount($query);
                
                $query->offset($this->collection->pagination->offset);
                $query->limit($this->collection->pagination->limit);
                $query->orderBy(['[[products.date]]'=>SORT_DESC]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

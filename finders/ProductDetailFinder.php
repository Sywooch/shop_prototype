<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;
use app\filters\ProductsFiltersInterface;

/**
 * Возвращает коллекцию товаров из СУБД
 */
class ProductDetailFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий запрашиваемый товар
     */
    public $seocode;
    
    public function rules()
    {
        return [
            [['seocode'], 'required']
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
            if (empty($this->seocode)) {
                throw new ErrorException($this->emptyError('seocode'));
            }
            
            if ($this->collection->isEmpty()) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.code]]', '[[products.name]]', '[[products.price]]', '[[products.description]]', '[[products.images]]', '[[products.seocode]]', '[[products.id_category]]', '[[products.id_subcategory]]']);
                $query->where(['[[products.seocode]]'=>$this->seocode]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use yii\helpers\ArrayHelper;
use app\models\ProductsModel;

/**
 * Возвращает похожие товары
 */
class RelatedFinder extends AbstractBaseFinder
{
    /**
     * @var ProductsModel, для которого будут найдены связанные
     */
    private $product;
    /**
     * @var array загруженных ProductsModel
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
                if (empty($this->product)) {
                    throw new ErrorException($this->emptyError('product'));
                }
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.images]]', '[[products.seocode]]', '[[products.id_category]]', '[[products.id_subcategory]]']);
                $query->innerJoin('{{related_products}}', '[[products.id]]=[[related_products.id_related_product]]');
                $query->where(['[[related_products.id_product]]'=>$this->product->id]);
                $query->limit(3);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel RelatedFinder::product
     * @param ProductsModel $product
     */
    public function setProduct(ProductsModel $product)
    {
        try {
            $this->product = $product;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

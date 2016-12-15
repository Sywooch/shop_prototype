<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use yii\helpers\ArrayHelper;
use app\models\ProductsModel;

/**
 * Возвращает похожие товары
 */
class SimilarFinder extends AbstractBaseFinder
{
    /**
     * @var ProductsModel, для которого будут найдены похожие
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
                $query->where(['!=', '[[products.id]]', $this->product->id]);
                $query->andWhere(['[[products.id_category]]'=>$this->product->id_category]);
                $query->andWhere(['[[products.id_subcategory]]'=>$this->product->id_subcategory]);
                $query->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_product]]');
                $query->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($this->product->colors, 'id')]);
                $query->innerJoin('{{products_sizes}}', '[[products.id]]=[[products_sizes.id_product]]');
                $query->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($this->product->sizes, 'id')]);
                $query->limit(3);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel SimilarFinder::product
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

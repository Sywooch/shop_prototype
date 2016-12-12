<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию товаров из СУБД
 */
class SimilarProducts extends AbstractBaseFinder
{
    /**
     * @var Model товар, для которого будут получены похожие товары
     */
    private $product;
    /**
     * @var CollectionInterface коллекция цветов, в которых могут быть доступны похожие товары
     */
    private $colors;
    
    public function rules()
    {
        return [
            [['id_product', 'colors'], 'required']
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
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.seocode]]']);
                $query->where(['!=', '[[products.id]]', $this->product->id]);
                $query->andWhere(['[[products.id_category]]'=>$this->product->id_category]);
                $query->andWhere(['[[products.id_subcategory]]'=>$this->product->id_subcategory]);
                $query->innerJoin('{{products_colors}}', '[[products.id]]=[[products_colors.id_products]]');
                $query->andWhere(['[[products_colors.id_color]]'=>$this->colors->column('id')]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

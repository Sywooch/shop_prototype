<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\models\{CommentsModel,
    ProductsModel};

/**
 * Возвращает похожие товары
 */
class CommentsProductFinder extends AbstractBaseFinder
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
                
                $query = CommentsModel::find();
                $query->select(['[[comments.date]]', '[[comments.text]]', '[[comments.name]]']);
                $query->where(['[[comments.id_product]]'=>$this->product->id]);
                $query->andWhere(['[[comments.active]]'=>true]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel CommentsFinder::product
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

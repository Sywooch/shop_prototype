<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает array ProductsModel из СУБД
 */
class PopularProductsFinder extends AbstractBaseFinder
{
    /**
     * @var ProductsModel
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
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]', '[[products.views]]']);
                $query->where(['>', '[[products.views]]', 0]);
                $query->orderBy(['[[products.views]]'=>SORT_DESC]);
                $query->limit(\Yii::$app->params['popularLimit']);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

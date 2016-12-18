<?php

namespace app\finders;

use yii\db\Query;
use app\models\BrandsModel;

/**
 * Коллекция методов, общих для BrandsFilterFinder, BrandsFilterSphinxFinder
 */
trait BrandsFilterFindersTrait
{
    /**
     * Создает объект запроса
     * @return Query
     */
    public function createQuery(): Query
    {
        try {
            $query = BrandsModel::find();
            $query->select(['[[brands.id]]', '[[brands.brand]]']);
            $query->distinct();
            $query->innerJoin('{{products}}', '[[products.id_brand]]=[[brands.id]]');
            $query->where(['[[products.active]]'=>true]);
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

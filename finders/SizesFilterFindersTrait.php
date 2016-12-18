<?php

namespace app\finders;

use yii\db\Query;
use app\models\SizesModel;

/**
 * Коллекция методов, общих для SizesFilterFinder, SizesFilterSphinxFinder
 */
trait SizesFilterFindersTrait
{
    /**
     * Создает объект запроса
     * @return Query
     */
    public function createQuery(): Query
    {
        try {
            $query = SizesModel::find();
            $query->select(['[[sizes.id]]', '[[sizes.size]]']);
            $query->distinct();
            $query->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
            $query->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
            $query->where(['[[products.active]]'=>true]);
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\db\Query;
use app\models\ColorsModel;

/**
 * Коллекция методов, общих для ColorsFilterFinder, ColorsFilterSphinxFinder
 */
trait ColorsFilterFindersTrait
{
    /**
     * Создает объект запроса
     * @return Query
     */
    public function createQuery(): Query
    {
        try {
            $query = ColorsModel::find();
            $query->select(['[[colors.id]]', '[[colors.color]]']);
            $query->distinct();
            $query->innerJoin('{{products_colors}}', '[[colors.id]]=[[products_colors.id_color]]');
            $query->innerJoin('{{products}}', '[[products_colors.id_product]]=[[products.id]]');
            $query->where(['[[products.active]]'=>true]);
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

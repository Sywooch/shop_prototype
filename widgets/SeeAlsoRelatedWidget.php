<?php

namespace app\widgets;

use app\widgets\SeeAlsoWidget;
use app\queries\QueryCriteria;
use app\filters\{JoinFilter,
    WhereFilter};

/**
 * Формирует HTML строку с информацией о похожих товарах
 */
class SeeAlsoRelatedWidget extends SeeAlsoWidget
{
    /**
     * Конструирует HTML строку с информацией о похожих товарах
     * @return string
     */
    public function run()
    {
        try {
            $criteria = $this->repository->criteria;
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{related_products}}', 'condition'=>'[[related_products.id_related_product]]=[[products.id]]']]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[related_products.id_product]]'=>$this->model->id]]));
            
            return parent::run();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

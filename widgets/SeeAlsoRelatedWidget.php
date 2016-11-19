<?php

namespace app\widgets;

use app\widgets\SeeAlsoWidget;
use app\models\QueryCriteria;

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
            $criteria = new QueryCriteria();
            $criteria->join('INNER JOIN', '{{related_products}}', '[[related_products.id_related_product]]=[[products.id]]');
            $criteria->where(['[[related_products.id_product]]'=>$this->model->id]);
            $this->repository->setCriteria($criteria);
            
            return parent::run();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

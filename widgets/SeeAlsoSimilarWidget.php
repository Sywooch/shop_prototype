<?php

namespace app\widgets;

use yii\helpers\ArrayHelper;
use app\widgets\SeeAlsoWidget;
use app\queries\QueryCriteria;
use app\filters\{DistinctFilter,
    JoinFilter,
    LimitFilter,
    WhereFilter};

/**
 * Формирует HTML строку с информацией о похожих товарах
 */
class SeeAlsoSimilarWidget extends SeeAlsoWidget
{
    /**
     * Конструирует HTML строку с информацией о похожих товарах
     * @return string
     */
    public function run()
    {
        try {
            $criteria = $this->repository->criteria;
            $criteria->setFilter(new DistinctFilter());
            $criteria->setFilter(new WhereFilter(['condition'=>['!=', '[[id]]', $this->model->id]]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[id_category]]'=>$this->model->category->id]]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[id_subcategory]]'=>$this->model->subcategory->id]]));
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products_colors}}', 'condition'=>'[[products_colors.id_product]]=[[products.id]]']]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products_colors.id_color]]'=>ArrayHelper::getColumn($this->model->colors, 'id')]]));
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products_sizes}}', 'condition'=>'[[products_sizes.id_product]]=[[products.id]]']]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($this->model->sizes, 'id')]]));
            $criteria->setFilter(new LimitFilter(['condition'=>\Yii::$app->params['similarLimit']]));
            
            return parent::run();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

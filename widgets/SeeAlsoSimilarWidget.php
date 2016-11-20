<?php

namespace app\widgets;

use yii\helpers\ArrayHelper;
use app\widgets\SeeAlsoWidget;
use app\models\QueryCriteria;

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
            $criteria = $this->repository->getCriteria();
            $criteria->distinct();
            $criteria->where(['!=', '[[id]]', $this->model->id]);
            $criteria->where(['[[id_category]]'=>$this->model->category->id]);
            $criteria->where(['[[id_subcategory]]'=>$this->model->subcategory->id]);
            $criteria->join('INNER JOIN', '{{products_colors}}', '[[products_colors.id_product]]=[[products.id]]');
            $criteria->where(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($this->model->colors, 'id')]);
            $criteria->join('INNER JOIN', '{{products_sizes}}', '[[products_sizes.id_product]]=[[products.id]]');
            $criteria->where(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($this->model->sizes, 'id')]);
            $criteria->limit(\Yii::$app->params['similarLimit']);
            
            return parent::run();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

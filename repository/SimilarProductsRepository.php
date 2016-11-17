<?php

namespace app\repository;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

class SimilarProductsRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getGroup($model): array
    {
        try {
            if (!$model instanceof ProductsModel) {
                throw new ErrorException(ExceptionsTrait::emptyError('ProductsModel'));
            }
            
            if (empty($this->items)) {
                $query = ProductsModel::find();
                $query->distinct();
                $query->where(['!=', '[[id]]', $model->id]);
                $query->andWhere(['[[id_category]]'=>$model->category->id]);
                $query->andWhere(['[[id_subcategory]]'=>$model->subcategory->id]);
                $query->innerJoin('{{products_colors}}', '[[products_colors.id_product]]=[[products.id]]');
                $query->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($model->colors, 'id')]);
                $query->innerJoin('{{products_sizes}}', '[[products_sizes.id_product]]=[[products.id]]');
                $query->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($model->sizes, 'id')]);
                $query->limit(\Yii::$app->params['similarLimit']);
                $data = $query->all();
                if (!empty($data)) {
                    $this->items = $data;
                }
            }
            
            return $this->items;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

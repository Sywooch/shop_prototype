<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;
use app\interfaces\FinderSearchInterface;

class ProductsFinder extends Model implements FinderSearchInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий поиска 1 товара
     */
    const DETAIL = 'detail';
    /**
     * Сценарий поиска похожих товаров
     */
    const SIMILAR = 'similar';
    /**
     * Сценарий поиска связанных товаров
     */
    const RELATED = 'related';
    
    /**
     * Принимает запрос на поиск данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function search(string $scenario, $data)
    {
        try {
            switch ($scenario) {
                case self::DETAIL:
                    return $this->detail($data);
                case self::SIMILAR:
                    return $this->similar($data);
                case self::RELATED:
                    return $this->related($data);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные 1 товара
     * @param array $data данные $_GET запроса 
     * @return ProductsModel
     */
    private function detail(array $data): ProductsModel
    {
        try {
            if (empty($data['seocode'])) {
                throw new ErrorException(ExceptionsTrait::emptyError('$data[\'seocode\']'));
            }
            
            $model = ProductsModel::find()->where('seocode=:seocode', [':seocode'=>$data['seocode']])->one();
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ProductsModel, похожих по характеристикам на исходный
     * @param object $productsModel ProductsModel
     * @return array
     */
    private function similar(ProductsModel $productsModel): array
    {
        try {
            $similarQuery = ProductsModel::find();
            $similarQuery->distinct();
            $similarQuery->where(['!=', '[[id]]', $productsModel->id]);
            $similarQuery->andWhere(['[[id_category]]'=>$productsModel->category->id]);
            $similarQuery->andWhere(['[[id_subcategory]]'=>$productsModel->subcategory->id]);
            $similarQuery->innerJoin('{{products_colors}}', '[[products_colors.id_product]]=[[products.id]]');
            $similarQuery->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($productsModel->colors, 'id')]);
            $similarQuery->innerJoin('{{products_sizes}}', '[[products_sizes.id_product]]=[[products.id]]');
            $similarQuery->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($productsModel->sizes, 'id')]);
            $similarQuery->limit(\Yii::$app->params['similarLimit']);
            $similarArray = $similarQuery->all();
            
            return $similarArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив ProductsModel, связанных с исходным
     * @param object $productsModel ProductsModel
     * @return array
     */
    private function related(ProductsModel $productsModel): array
    {
        try {
            $relatedQuery = ProductsModel::find();
            $relatedQuery->innerJoin('{{related_products}}', '[[related_products.id_related_product]]=[[products.id]]');
            $relatedQuery->where(['[[related_products.id_product]]'=>$productsModel->id]);
            $relatedArray = $relatedQuery->all();
            
            return $relatedArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use yii\db\ActiveQuery;
use app\finders\AbstractBaseFinder;
use app\filters\AdminProductsFiltersInterface;
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class AdminProductsCsvFinder extends AbstractBaseFinder
{
    /**
     * @var AdminProductsFiltersInterface объект товарных фильтров
     */
    private $filters;
    /**
     * @var ProductsCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find(): ActiveQuery
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.date]]', '[[products.code]]', '[[products.name]]', '[[products.description]]', '[[products.short_description]]', '[[products.price]]', '[[products.images]]', '[[products.id_category]]', '[[products.id_subcategory]]', '[[products.id_brand]]', '[[products.active]]', '[[products.total_products]]', '[[products.seocode]]', '[[products.views]]']);
                $query->with('category', 'subcategory', 'brand', 'colors', 'sizes');
                
                if (!empty($this->filters->active)) {
                    $query->andWhere(['[[products.active]]'=>$this->filters->active]);
                }
                
                if (!empty($this->filters->colors)) {
                    $query->innerJoin('{{products_colors}}', '[[products_colors.id_product]]=[[products.id]]');
                    $query->innerJoin('{{colors}}', '[[colors.id]]=[[products_colors.id_color]]');
                    $query->andWhere(['[[colors.id]]'=>$this->filters->colors]);
                }
                
                if (!empty($this->filters->sizes)) {
                    $query->innerJoin('{{products_sizes}}', '[[products_sizes.id_product]]=[[products.id]]');
                    $query->innerJoin('{{sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                    $query->andWhere(['[[sizes.id]]'=>$this->filters->sizes]);
                }
                
                if (!empty($this->filters->brands)) {
                    $query->andWhere(['[[products.id_brand]]'=>$this->filters->brands]);
                }
                
                if (!empty($this->filters->category)) {
                    $query->andWhere(['[[products.id_category]]'=>$this->filters->category]);
                }
                
                if (!empty($this->filters->subcategory)) {
                    $query->andWhere(['[[products.id_subcategory]]'=>$this->filters->subcategory]);
                }
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[products.' . $sortingField . ']]'=>(int) $sortingType]);
                
                $this->storage = $query;
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает AdminProductsFiltersInterface AdminProductsCsvFinder::filters
     * @param AdminProductsFiltersInterface $filters
     */
    public function setFilters(AdminProductsFiltersInterface $filters)
    {
        try {
            $this->filters = $filters;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\finders;

use yii\db\Query;
use app\models\ProductsModel;
use app\collections\{LightPagination,
    ProductsCollection};

/**
 * Коллекция методов, общих для ProductsFinder, ProductsSphinxFinder
 */
trait ProductsFindersTrait
{
    /**
     * Создает объект коллекции
     */
    public function createCollection()
    {
        try {
            $this->storage = new ProductsCollection(['pagination'=>new LightPagination()]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Создает объект запроса
     * @return Query
     */
    public function createQuery(): Query
    {
        try {
            $query = ProductsModel::find();
            $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
            $query->where(['[[products.active]]'=>true]);
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет фильтры к запросу
     * @param Query $query
     * @return Query
     */
    public function addFilters(Query $query): Query
    {
        try {
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
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конфигурирует пагинатор, добавляет LIMIT OFFSET к запросу
     * @param Query $query
     * @return Query
     */
    public function addPagination(Query $query): Query
    {
        try {
            $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
            $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
            $this->storage->pagination->setTotalCount($query);
            
            $query->offset($this->storage->pagination->offset);
            $query->limit($this->storage->pagination->limit);
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет сортировку к запросу
     * @param Query $query
     * @return Query
     */
    public function addSorting(Query $query): Query
    {
        try {
            $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
            $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
            $query->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
            
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает данные и добавляет их в коллекцию
     * @param Query $query
     */
    public function get(Query $query)
    {
        try {
            $array = $query->all();
            
            if (!empty($array)) {
                foreach ($array as $model) {
                    $this->storage->add($model);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

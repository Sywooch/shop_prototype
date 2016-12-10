<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;
use app\collections\CollectionInterface;
use app\filters\ProductsFiltersInterface;

/**
 * Возвращает коллекцию товаров из СУБД
 */
class ProductsSphinxFinder extends AbstractBaseFinder
{
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    public $found;
    /**
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    public $page;
    /**
     * @var ProductsFiltersInterface объект данных товарных фильтров
     */
    private $filters;
    
    public function rules()
    {
        return [
            [['found', 'page'], 'safe'],
            [['found'], 'required'],
        ];
    }
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find(): CollectionInterface
    {
        try {
            if (empty($this->collection)) {
                throw new ErrorException($this->emptyError('collection'));
            }
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if ($this->collection->isEmpty()) {
                if ($this->validate() === false) {
                    throw new ErrorException($this->modelError($this->errors));
                }
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.active]]'=>true]);
                $query->andWhere(['[[products.id]]'=>$this->found]);
                
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
                
                $this->collection->pagination->pageSize = \Yii::$app->params['limit'];
                $this->collection->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->collection->pagination->setTotalCount($query);
                
                $query->offset($this->collection->pagination->offset);
                $query->limit($this->collection->pagination->limit);
                
                $sortingField = !empty($this->filters->sortingField) ? $this->filters->sortingField : 'date';
                $sortingType = (!empty($this->filters->sortingType) && $this->filters->sortingType === 'SORT_ASC') ? SORT_ASC : SORT_DESC;
                $query->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
                
                $this->collection->query = $query;
            }
            
            return $this->collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsFiltersInterface ProductsSphinxFinder::filters
     * @param ProductsFiltersInterface $filters
     */
    public function setFilters(ProductsFiltersInterface $filters)
    {
        try {
            $this->filters = $filters;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

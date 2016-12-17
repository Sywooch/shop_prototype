<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\ProductsModel;
use app\finders\AbstractBaseFinder;
use app\collections\{LightPagination,
    ProductsCollection};
use app\filters\ProductsFiltersInterface;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsSphinxFinder extends AbstractBaseFinder
{
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    private $sphinx;
    /**
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    public $page;
    /**
     * @var ProductsFiltersInterface объект товарных фильтров
     */
    private $filters;
    /**
     * @var array загруженных ProductsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                if (empty($this->sphinx)) {
                    throw new ErrorException($this->emptyError('sphinx'));
                }
                if (empty($this->filters)) {
                    throw new ErrorException($this->emptyError('filters'));
                }
                
                $this->storage = new ProductsCollection(['pagination'=>new LightPagination()]);
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.active]]'=>true]);
                
                $query->andWhere(['[[products.id]]'=>$this->sphinx]);
                
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
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
                
                $array = $query->all();
                
                if (!empty($array)) {
                    foreach ($array as $model) {
                        $this->storage->add($model);
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ProductsSphinxFinder::sphinx
     * @param array $sphinx
     */
    public function setSphinx(array $sphinx)
    {
        try {
            $this->sphinx = $sphinx;
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

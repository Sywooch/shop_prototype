<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\filters\AdminProductsFiltersInterface;
use app\collections\{LightPagination,
    ProductsCollection};
use app\models\ProductsModel;

/**
 * Возвращает array ProductsModel из СУБД
 */
class AdminProductsFinder extends AbstractBaseFinder
{
    /**
     * @var AdminProductsFiltersInterface объект товарных фильтров
     */
    private $filters;
    /**
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    private $page;
    /**
     * @var ProductsCollection
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find(): ProductsCollection
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $this->storage = new ProductsCollection(['pagination'=>new LightPagination()]);
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.date]]', '[[products.code]]', '[[products.name]]', '[[products.description]]', '[[products.short_description]]', '[[products.price]]', '[[products.images]]', '[[products.id_category]]', '[[products.id_subcategory]]', '[[products.id_brand]]', '[[products.active]]', '[[products.total_products]]', '[[products.seocode]]', '[[products.views]]', ]);
                $query->with('category', 'subcategory', 'colors', 'sizes', 'brand', 'related');
                
                if ($this->filters->active === ACTIVE_STATUS || $this->filters->active === INACTIVE_STATUS) {
                    $query->where(['[[products.active]]'=>$this->filters->active]);
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
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[products.' . $sortingField . ']]'=>(int) $sortingType]);
                
                $productsModelArray = $query->all();
                
                if (!empty($productsModelArray)) {
                    foreach ($productsModelArray as $model) {
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
     * Присваивает AdminProductsFiltersInterface AdminProductsFinder::filters
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
    
    /**
     * Присваивает номер страницы AdminProductsFinder::page
     * @param int $page
     */
    public function setPage(int $page)
    {
        try {
            $this->page = $page;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

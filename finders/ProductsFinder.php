<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\AbstractBaseFinder;
use app\filters\ProductsFiltersInterface;
use app\collections\{LightPagination,
    ProductsCollection};
use app\models\ProductsModel;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsFinder extends AbstractBaseFinder
{
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    private $category;
    /**
     * @var string GET параметр, определяющий текущую подкатегорию каталога товаров
     */
    private $subcategory;
    /**
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    private $page;
    /**
     * @var ProductsFiltersInterface объект товарных фильтров
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
    public function find()
    {
        try {
            if (empty($this->filters)) {
                throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $this->storage = new ProductsCollection(['pagination'=>new LightPagination()]);
                
                $query = ProductsModel::find();
                $query->select(['[[products.id]]', '[[products.name]]', '[[products.price]]', '[[products.short_description]]', '[[products.images]]', '[[products.seocode]]']);
                $query->where(['[[products.active]]'=>true]);
                $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $query->andWhere(['[[categories.active]]'=>true]);
                $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                $query->andWhere(['[[subcategory.active]]'=>true]);
                
                if (!empty($this->category)) {
                    //$query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                    $query->andWhere(['[[categories.seocode]]'=>$this->category]);
                    //$query->andWhere(['[[categories.active]]'=>true]);
                    if (!empty($this->subcategory)) {
                        //$query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                        //$query->andWhere(['[[subcategory.active]]'=>true]);
                    }
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
                
                $this->storage->pagination->pageSize = \Yii::$app->params['limit'];
                $this->storage->pagination->page = !empty($this->page) ? (int) $this->page - 1 : 0;
                $this->storage->pagination->setTotalCount($query);
                
                $query->offset($this->storage->pagination->offset);
                $query->limit($this->storage->pagination->limit);
                
                $sortingField = $this->filters->sortingField ?? \Yii::$app->params['sortingField'];
                $sortingType = $this->filters->sortingType ?? \Yii::$app->params['sortingType'];
                $query->orderBy(['[[products.' . $sortingField . ']]'=>(int) $sortingType]);
                
                $productsArray = $query->all();
                
                if (!empty($productsArray)) {
                    foreach ($productsArray as $product) {
                        $this->storage->add($product);
                    }
                }
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsFiltersInterface ProductsFinder::filters
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
    
    /**
     * Присваивает категорию ProductsFinder::category
     * @param string $category
     */
    public function setCategory(string $category)
    {
        try {
            $this->category = $category;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает подкатегорию ProductsFinder::subcategory
     * @param string $category
     */
    public function setSubcategory(string $subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает номер страницы ProductsFinder::page
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

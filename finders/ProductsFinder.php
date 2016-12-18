<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\{AbstractBaseFinder,
    ProductsFindersTrait};
use app\filters\ProductsFiltersInterface;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsFinder extends AbstractBaseFinder
{
    use ProductsFindersTrait;
    
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    public $category;
    /**
     * @var string GET параметр, определяющий текущую подкатегорию каталога товаров
     */
    public $subcategory;
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
                if (empty($this->filters)) {
                    throw new ErrorException($this->emptyError('filters'));
                }
                
                $this->createCollection();
                
                $query = $this->createQuery();
                
                if (!empty($this->category)) {
                    $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                    $query->andWhere(['[[categories.seocode]]'=>$this->category]);
                    if (!empty($this->subcategory)) {
                        $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                    }
                }
                
                $query = $this->addFilters($query);
                
                $query = $this->addPagination($query);
                
                $query = $this->addSorting($query);
                
                $this->get($query);
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
}

<?php

namespace app\finders;

use yii\base\ErrorException;
use app\finders\{AbstractBaseFinder,
    ProductsFindersTrait};
use app\filters\ProductsFiltersInterface;

/**
 * Возвращает ProductsModel выбранного товара из СУБД
 */
class ProductsSphinxFinder extends AbstractBaseFinder
{
    use ProductsFindersTrait;
    
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    private $sphinx;
    /**
     * @var string GET параметр, определяющий текущую страницу каталога товаров
     */
    private $page;
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
            if (empty($this->sphinx)) {
                throw new ErrorException($this->emptyError('sphinx'));
            }
            if (empty($this->filters)) {
                   throw new ErrorException($this->emptyError('filters'));
            }
            
            if (empty($this->storage)) {
                $this->createCollection();
                
                $query = $this->createQuery();
                
                $query->andWhere(['[[products.id]]'=>$this->sphinx]);
                
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
    
    /**
     * Присваивает номер страницы ProductsSphinxFinder::page
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

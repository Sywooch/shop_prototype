<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\AbstractBaseService;
use app\finders\{AdminProductsFiltersSessionFinder,
    AdminProductsFinder};
use app\helpers\HashHelper;
use app\collections\ProductsCollection;

/**
 * Возвращает объект ProductsCollection
 */
class AdminProductsCollectionService extends AbstractBaseService
{
    /**
     * @var string ключ для получения фильтров из сессии
     */
    private $key;
    /**
     * @var int номер страницы
     */
    private $page;
    /**
     * @var ProductsCollection
     */
    private $productsCollection = null;
    
    /**
     * Возвращает ProductsCollection
     * @return ProductsCollection
     */
    public function get(): ProductsCollection
    {
        try {
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            
            if (empty($this->productsCollection)) {
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>$this->key,
                ]);
                $filtersModel = $finder->find();
                
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(AdminProductsFinder::class, [
                    'page'=>$this->page ?? 0,
                    'filters'=>$filtersModel
                ]);
                
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ключ AdminProductsCollectionService::key
     * @param string $key
     */
    public function setKey(string $key)
    {
        try {
            $this->key = $key;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ключ AdminProductsCollectionService::page
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

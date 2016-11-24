<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\models\ProductsCollection;
use app\services\SearchServiceInterface;

class GoodsListSearchService extends Object implements SearchServiceInterface
{
    use ExceptionsTrait;
    
     /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    private $repository;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на поиск списка товаров
     * @param object Request
     * @return ProductsCollection
     */
    public function search($request): ProductsCollection
    {
        try {
            $criteria = $this->repository->getCriteria();
            
            $criteria->where(['[[products.active]]'=>true]);
            if (!empty($request[\Yii::$app->params['categoryKey']])) {
                $criteria->join('INNER JOIN', '{{categories}}', '[[categories.id]]=[[products.id_category]]');
                $criteria->where(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
                if (!empty($request[\Yii::$app->params['subcategoryKey']])) {
                    $criteria->join('INNER JOIN', '{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                    $criteria->where(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
                }
            }
            $pagination = $this->repository->collection->pagination;
            $pagination->setRequest($request);
            $criteria->setPagination($pagination);
            $criteria->offset();
            $criteria->limit();
            
            $collection = $this->repository->getGroup();
            
            return $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству ProductSearchService::repository
     * @param object $repository RepositoryInterface
     */
    public function setRepository(RepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

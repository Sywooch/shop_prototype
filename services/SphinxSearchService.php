<?php

namespace app\services;

use yii\base\{ErrorException,
    Object};
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\models\CollectionInterface;
use app\services\SearchServiceInterface;
use app\filters\{FromFilter,
    SelectFilter};

class SphinxSearchService extends Object implements SearchServiceInterface
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
     * @return CollectionInterface
     */
    public function search($request): CollectionInterface
    {
        try {
            $criteria = $this->repository->criteria;
            
            $criteria->setFilter(new SelectFilter(['condition'=>['id']]));
            $criteria->setFilter(new FromFilter(['condition'=>'{{shop}}']));
            
            
            
            
            
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products.active]]'=>true]]));
            
            if (!empty($request[\Yii::$app->params['categoryKey']])) {
                $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{categories}}', 'condition'=>'[[categories.id]]=[[products.id_category]]']]));
                $criteria->setFilter(new WhereFilter(['condition'=>['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]]));
                if (!empty($request[\Yii::$app->params['subcategoryKey']])) {
                    $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{subcategory}}', 'condition'=>'[[subcategory.id]]=[[products.id_subcategory]]']]));
                    $criteria->setFilter(new WhereFilter(['condition'=>['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]]));
                }
            }
            
            $pagination = $this->repository->collection->pagination;
            $pagination->pageSize = \Yii::$app->params['limit'];
            $pagination->page = !empty($request[\Yii::$app->params['pagePointer']]) ? $request[\Yii::$app->params['pagePointer']] - 1 : 0;
            
            $criteria->setFilter(new OffsetFilter(['condition'=>$pagination->offset]));
            $criteria->setFilter(new LimitFilter(['condition'=>$pagination->limit]));
            
            $criteria->setFilter(new SortingFilter());
            
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

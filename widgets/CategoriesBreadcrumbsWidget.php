<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\filters\WhereFilter;

/**
 * Формирует breadcrumbs для страницы товара
 */
class CategoriesBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    public $service;
    /**
     * @var string seocode категории
     */
    public $category;
    /**
     * @var string seocode подкатегории
     */
    public $subcategory;
    
    public function init()
    {
        try {
            if (empty($this->service)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
            
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            if (!empty($this->category)) {
                $category = $this->service->handle($this->category);
                if ($category === null) {
                    throw new ErrorException(ExceptionsTrait::emptyError('category'));
                }
                
                \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$category->seocode], 'label'=>$category->name];
                
                if (!empty($this->subcategory)) {
                    $subcategoryArray = $category->subcategory;
                    foreach ($subcategoryArray as $entity) {
                        if ($entity->seocode === $this->subcategory) {
                            $subcategory = $entity;
                            break;
                        }
                    }
                    \Yii::$app->params['breadcrumbs'][] = ['url'=>['/products-list/index', \Yii::$app->params['categoryKey']=>$category->seocode, \Yii::$app->params['subcategoryKey']=>$subcategory->seocode], 'label'=>$subcategory->name];
                }
            }
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству CategoriesBreadcrumbsWidget::repository
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

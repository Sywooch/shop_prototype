<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\filters\{DistinctFilter,
    FromFilter,
    JoinFilter,
    MatchFilter,
    SelectFilter,
    WhereFilter};
use app\queries\CriteriaInterface;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class FiltersWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface
     */
    public $colorsService;
    /**
     * @var object RepositoryInterface
     */
    public $sizesService;
    /**
     * @var object RepositoryInterface
     */
    public $brandsService;
    /**
     * @var object RepositoryInterface
     */
    public $sphinxService;
    /**
     * @var object ActiveRecord/Model, получает данные из формы
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            /*if (empty($this->colorsRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('colorsRepository'));
            }
            if (empty($this->sizesRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('sizesRepository'));
            }
            if (empty($this->brandsRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('brandsRepository'));
            }*/
            if (empty($this->form)) {
                throw new ErrorException(ExceptionsTrait::emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Filters');
            $renderArray['formModel'] = $this->form;
            $renderArray['sortingFieldsList'] = ['date'=>\Yii::t('base', 'Sorting by date'), 'price'=>\Yii::t('base', 'Sorting by price')];
            $renderArray['sortingTypeList'] = ['SORT_ASC'=>\Yii::t('base', 'Sort ascending'), 'SORT_DESC'=>\Yii::t('base', 'Sort descending')];
            
            $colorsCollection = $this->colorsService->search(\Yii::$app->request->get());
            $colorsCollection->sort('color', SORT_ASC);
            $renderArray['colorsCollection'] = $colorsCollection->map('id', 'color');
           
            $sizesCollection = $this->sizesService->search(\Yii::$app->request->get());
            $sizesCollection->sort('size', SORT_ASC);
            $renderArray['sizesCollection'] = $sizesCollection->map('id', 'size');
           
            $brandsCollection = $this->brandsService->search(\Yii::$app->request->get());
            $brandsCollection->sort('brand', SORT_ASC);
            $renderArray['brandsCollection'] = $brandsCollection->map('id', 'brand');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::colorsRepository
     * @param object $repository RepositoryInterface
     */
    public function setColorsRepository(RepositoryInterface $repository)
    {
        try {
            $this->colorsRepository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::sizesRepository
     * @param object $repository RepositoryInterface
     */
    public function setSizesRepository(RepositoryInterface $repository)
    {
        try {
            $this->sizesRepository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::brandsRepository
     * @param object $repository RepositoryInterface
     */
    public function setBrandsRepository(RepositoryInterface $repository)
    {
        try {
            $this->brandsRepository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::sphinxRepository
     * @param object $repository RepositoryInterface
     */
    public function setSphinxRepository(RepositoryInterface $repository)
    {
        try {
            $this->sphinxRepository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству FiltersWidget::form
     * @param object $model Model
     */
    public function setForm(Model $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет фильры по категории и подкатегории
     * @param object $criteria CriteriaInterface
     */
    private function addCategory(CriteriaInterface $criteria): CriteriaInterface
    {
        try {
            if (!empty($category = \Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{categories}}', 'condition'=>'[[products.id_category]]=[[categories.id]]']]));
                $criteria->setFilter(new WhereFilter(['condition'=>['[[categories.seocode]]'=>$category]]));
                if (!empty($subcategory = \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                    $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{subcategory}}', 'condition'=>'[[products.id_subcategory]]=[[subcategory.id]]']]));
                    $criteria->setFilter(new WhereFilter(['condition'=>['[[subcategory.seocode]]'=>$subcategory]]));
                }
            }
            
            return $criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

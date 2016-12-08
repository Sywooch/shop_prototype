<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\collections\CollectionInterface;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class FiltersWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object CollectionInterface
     */
    private $colorsCollection;
    /**
     * @var object CollectionInterface
     */
    private $sizesCollection;
    /**
     * @var object CollectionInterface
     */
    private $brandsCollection;
    /**
     * @var object Model, получает данные из формы
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->colorsCollection)) {
                throw new ErrorException($this->emptyError('colorsCollection'));
            }
            if (empty($this->sizesCollection)) {
                throw new ErrorException($this->emptyError('sizesCollection'));
            }
            if (empty($this->brandsCollection)) {
                throw new ErrorException($this->emptyError('brandsCollection'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Filters');
            $renderArray['formModel'] = $this->form;
            $renderArray['sortingFieldsList'] = ['date'=>\Yii::t('base', 'Sorting by date'), 'price'=>\Yii::t('base', 'Sorting by price')];
            $renderArray['sortingTypeList'] = ['SORT_ASC'=>\Yii::t('base', 'Sort ascending'), 'SORT_DESC'=>\Yii::t('base', 'Sort descending')];
            
            $this->colorsCollection->sort('color');
            $renderArray['colorsCollection'] = $this->colorsCollection->map('id', 'color');
           
            $this->sizesCollection->sort('size');
            $renderArray['sizesCollection'] = $this->sizesCollection->map('id', 'size');
           
            $this->brandsCollection->sort('brand');
            $renderArray['brandsCollection'] = $this->brandsCollection->map('id', 'brand');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству FiltersWidget::colorsCollection
     * @param object $colorsCollection CollectionInterface
     */
    public function setColorsCollection(CollectionInterface $collection)
    {
        try {
            $this->colorsCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству FiltersWidget::sizesCollection
     * @param object $sizesCollection CollectionInterface
     */
    public function setSizesCollection(CollectionInterface $collection)
    {
        try {
            $this->sizesCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству FiltersWidget::brandsCollection
     * @param object $brandsCollection CollectionInterface
     */
    public function setBrandsCollection(CollectionInterface $collection)
    {
        try {
            $this->brandsCollection = $collection;
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
}

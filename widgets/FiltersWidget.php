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
     * @var object CollectionInterface
     */
    private $sortingFieldsCollection;
    /**
     * @var object CollectionInterface
     */
    private $sortingTypesCollection;
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
            if (empty($this->sortingFieldsCollection)) {
                throw new ErrorException($this->emptyError('sortingFieldsCollection'));
            }
            if (empty($this->sortingTypesCollection)) {
                throw new ErrorException($this->emptyError('sortingTypesCollection'));
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
            
            $this->sortingFieldsCollection->sort('name');
            $renderArray['sortingFieldsCollection'] = $this->sortingFieldsCollection->map('name', 'value');
            
            $this->sortingTypesCollection->sort('name');
            $renderArray['sortingTypeCollection'] = $this->sortingTypesCollection->map('name', 'value');
            
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
     * Присваивает CollectionInterface свойству FiltersWidget::sortingFieldsCollection
     * @param object $brandsCollection CollectionInterface
     */
    public function setSortingFieldsCollection(CollectionInterface $collection)
    {
        try {
            $this->sortingFieldsCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству FiltersWidget::sortingTypesCollection
     * @param object $brandsCollection CollectionInterface
     */
    public function setSortingTypesCollection(CollectionInterface $collection)
    {
        try {
            $this->sortingTypesCollection = $collection;
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

<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;
use app\collections\CollectionInterface;

/**
 * Формирует меню
 */
class CategoriesMenuWidget extends Menu
{
    use ExceptionsTrait;
    
    /**
     * @var array
     */
    public $categories;
    /**
     * @var string основной route
     */
    public $rootRoute = '/products-list/index';
    /**
     * @var boolean помечать ли активным родительский пункт, если активен дочерний
     */
    public $activateParents = true;
    /**
     * @var string template для рендеринга sub-menu
     */
    public $submenuTemplate = "<ul>{items}</ul>";
    /**
     * @var array HTML атрибуты, которые будут применены к тегу-контейнеру меню (ul по-умолчанию)
     */
    public $options = ['class'=>'categories-menu'];
    
    public function init()
    {
        try {
            parent::init();
            
            $this->setItems();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Формирует массив ссылок для создания меню
     */
    private function setItems()
    {
        try {
            /*if (empty($this->categoriesCollection)) {
                throw new ErrorException($this->emptyError('categoriesCollection'));
            }*/
            
            foreach ($this->categories as $category) {
                if (empty($category->active)) {
                    continue;
                }
                $pack = [
                    'label'=>$category->name,
                    'url'=>[$this->rootRoute, \Yii::$app->params['categoryKey']=>$category->seocode]
                ];
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        if (empty($subcategory->active)) {
                            continue;
                        }
                        $pack['items'][] = [
                            'label'=>$subcategory->name,
                            'url'=>[$this->rootRoute, \Yii::$app->params['categoryKey']=>$category->seocode, \Yii::$app->params['subcategoryKey']=>$subcategory->seocode]
                        ];
                    }
                }
                $this->items[] = $pack;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству CategoriesMenuWidget::categoriesCollection
     * @param object $collection CollectionInterface
     */
    /*public function setCategoriesCollection(CollectionInterface $collection)
    {
        try {
            $this->categoriesCollection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }*/
}

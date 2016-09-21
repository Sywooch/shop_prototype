<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;

/**
 * Формирует меню
 */
class CategoriesMenuWidget extends Menu
{
    /**
     * @var array массив объектов CategoriesModel
     */
    public $categoriesList;
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
    public $options = ['class'=>'categoriesMenu'];
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->categoriesList)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$categoriesList']));
            }
            if (empty($this->rootRoute)) {
                throw new ErrorException(\Yii::t('base/errors', 'Not Evaluated {placeholder}!', ['placeholder'=>'$rootRoute']));
            }
            
            if (!$this->setItems()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error!'));
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует массив ссылок для создания меню
     * @return bool
     */
    private function setItems()
    {
        try {
            foreach ($this->categoriesList as $category) {
                /*if (empty($category->products)) {
                    continue;
                }*/
                $pack = [
                    'label'=>$category->name,
                    'url'=>[$this->rootRoute, \Yii::$app->params['categoryKey']=>$category->seocode]
                ];
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        /*if (empty($subcategory->products)) {
                            continue;
                        }*/
                        $pack['items'][] = [
                            'label'=>$subcategory->name,
                            'url'=>[$this->rootRoute, \Yii::$app->params['categoryKey']=>$category->seocode, \Yii::$app->params['subcategoryKey']=>$subcategory->seocode]
                        ];
                    }
                }
                $this->items[] = $pack;
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}

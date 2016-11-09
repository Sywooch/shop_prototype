<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\CategoriesModel;

/**
 * Формирует меню
 */
class CategoriesMenuWidget extends Menu
{
    use ExceptionsTrait;
    
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
            
            # Массив объектов CategoriesModel для формирования меню категорий
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name', 'seocode', 'active']);
            $categoriesQuery->with('subcategory');
            $categoriesQuery->asArray();
            $categoriesArray = $categoriesQuery->all();
            ArrayHelper::multisort($categoriesArray, 'name', SORT_ASC);
            $this->categoriesList = $categoriesArray;
            
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
            if (!empty($this->categoriesList)) {
                foreach ($this->categoriesList as $category) {
                    if (empty($category['active'])) {
                        continue;
                    }
                    $pack = [
                        'label'=>$category['name'],
                        'url'=>[$this->rootRoute, \Yii::$app->params['categoryKey']=>$category['seocode']]
                    ];
                    if (!empty($category['subcategory'])) {
                        foreach ($category['subcategory'] as $subcategory) {
                            if (empty($subcategory['active'])) {
                                continue;
                            }
                            $pack['items'][] = [
                                'label'=>$subcategory['name'],
                                'url'=>[$this->rootRoute, \Yii::$app->params['categoryKey']=>$category['seocode'], \Yii::$app->params['subcategoryKey']=>$subcategory['seocode']]
                            ];
                        }
                    }
                    $this->items[] = $pack;
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

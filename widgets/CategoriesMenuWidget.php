<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;
use app\models\CategoriesModel;
use app\repository\GetGroupRepositoryInterface;

/**
 * Формирует меню
 */
class CategoriesMenuWidget extends Menu
{
    use ExceptionsTrait;
    
    /**
     * @var object GetGroupRepositoryInterface для поиска данных по запросу
     */
    private $repository;
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
    /**
     * @var array массив объектов CategoriesModel
     */
    private $categoriesList;
    
    public function init()
    {
        try {
            parent::init();
            
            $this->categoriesList = $this->repository->getGroup();
            
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
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setRepository(GetGroupRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

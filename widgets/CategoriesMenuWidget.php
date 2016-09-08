<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\models\ProductsModel;

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
     * @var string template для рендеринга sub-menus
     */
    public $submenuTemplate = "<ul>{items}</ul>";
    /**
     * @var array HTML атрибуты, которые будут применены к тегу-контейнеру меню (ul по-умолчанию)
     */
    public $options = ['class'=>'categoriesMenu'];
    
    public function init()
    {
        parent::init();
        
        if (empty($this->categoriesList)) {
            throw new ErrorException('Отсуствуют данные для построения меню!');
        }
        if (empty($this->rootRoute)) {
            throw new ErrorException('Отсуствует route для построения меню!');
        }
            
        if (!$this->setItems()) {
            throw new ErrorException('Ошибка при формировании массива ссылок');
        }
    }
    
    /**
     * Формирует массив ссылок для создания меню
     * @return bool
     */
    private function setItems()
    {
        try {
            foreach ($this->categoriesList as $categories) {
                if (empty($categories->products)) {
                    continue;
                }
                $pack = [
                    'label'=>$categories->name,
                    'url'=>[$this->rootRoute, 'categories'=>$categories->seocode]
                ];
                if (!empty($categories->subcategory)) {
                    foreach ($categories->subcategory as $subcategory) {
                        $pack['items'][] = [
                            'label'=>$subcategory->name,
                            'url'=>[$this->rootRoute, 'categories'=>$categories->seocode, 'subcategory'=>$subcategory->seocode]
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

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
                throw new ErrorException('Отсуствуют данные для построения меню!');
            }
            if (empty($this->rootRoute)) {
                throw new ErrorException('Отсуствует route для построения меню!');
            }
            
            if (!$this->setItems()) {
                throw new ErrorException('Ошибка при формировании массива ссылок');
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
                if (empty($category->products)) {
                    continue;
                }
                $pack = [
                    'label'=>$category->name,
                    'url'=>[$this->rootRoute, 'category'=>$category->seocode]
                ];
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        $pack['items'][] = [
                            'label'=>$subcategory->name,
                            'url'=>[$this->rootRoute, 'category'=>$category->seocode, 'subcategory'=>$subcategory->seocode]
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

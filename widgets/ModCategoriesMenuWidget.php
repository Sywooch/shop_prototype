<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует меню
 */
class ModCategoriesMenuWidget extends AbstractBaseWidget
{
    /**
     * @var array CategoriesModel
     */
    private $categories;
    /**
     * @var string имя шаблона
     */
    private $template;
    /**
     * @var string текуший URL
     */
    private $currentUrl;
    /**
     * @var string основной route
     */
    private $rootRoute = '/products-list/index';
    
    /**
     * Формирует меню
     */
    public function run()
    {
        try {
            if (empty($this->categories)) {
                throw new ErrorException($this->emptyError('categories'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            if (empty($this->currentUrl)) {
                throw new ErrorException($this->emptyError('currentUrl'));
            }
            
            $renderArray = [];
            
            $name = \Yii::t('base', 'All catalog');
            $link = Url::to([$this->rootRoute]);
            $active = ($this->currentUrl === $link);
            $renderArray['categoriesArray'][] = [
                'name'=>$name,
                'link'=>$link,
                'active'=>$active
            ];
            
            foreach ($this->categories as $category) {
                if (empty($category->active)) {
                    continue;
                }
                
                $pack = [
                    'name'=>$category->name,
                    'link'=>false,
                    'active'=>false
                ];
                
                $name = \Yii::t('base', 'All');
                $link = Url::to([$this->rootRoute, \Yii::$app->params['categoryKey']=>$category->seocode]);
                $active = ($this->currentUrl === $link);
                $pack['subcategoryArray'][] = [
                    'name'=>$name,
                    'link'=>$link,
                    'active'=>$active
                ];
                if (!empty($category->subcategory)) {
                    foreach ($category->subcategory as $subcategory) {
                        if (empty($subcategory->active)) {
                            continue;
                        }
                        $name = $subcategory->name;
                        $link = Url::to([$this->rootRoute, \Yii::$app->params['categoryKey']=>$category->seocode, \Yii::$app->params['subcategoryKey']=>$subcategory->seocode]);
                        $active = ($this->currentUrl === $link);
                        $pack['subcategoryArray'][] = [
                            'name'=>$name,
                            'link'=>$link,
                            'active'=>$active
                        ];
                    }
                }
                $renderArray['categoriesArray'][] = $pack;
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModCategoriesMenuWidget::categories
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        try {
            $this->categories = $categories;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModCategoriesMenuWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModCategoriesMenuWidget::currentUrl
     * @param string $currentUrl
     */
    public function setCurrentUrl(string $currentUrl)
    {
        try {
            $this->currentUrl = $currentUrl;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение ModCategoriesMenuWidget::rootRoute
     * @param string $rootRoute
     */
    public function setRootRoute(string $rootRoute)
    {
        try {
            $this->rootRoute = $rootRoute;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

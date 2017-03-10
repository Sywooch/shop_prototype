<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\widgets\AbstractBaseWidget;
use app\models\CategoriesModel;

/**
 * Формирует HTML строку с тегами option категорий для втсавки в форму
 */
class CategoriesOptionWidget extends AbstractBaseWidget
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
     * Конструирует HTML строку
     * @return string
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
            
            $renderArray = [];
            
            $categoriesArray = ArrayHelper::map($this->categories, 'id', 'name');
            asort($categoriesArray, SORT_STRING);
            $renderArray['dataArray'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CategoriesOptionWidget::categories
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
     * Присваивает имя шаблона свойству CategoriesOptionWidget::template
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
}

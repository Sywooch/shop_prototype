<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\widgets\AbstractBaseWidget;
use app\models\SubcategoryModel;

/**
 * Формирует HTML строку с тегами option подкатегорий для втсавки в форму
 */
class SubcategoryOptionWidget extends AbstractBaseWidget
{
    /**
     * @var array SubcategoryModel
     */
    private $subcategoryArray;
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
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            if (!empty($this->subcategoryArray)) {
                $subcategoryArray = ArrayHelper::map($this->subcategoryArray, 'id', 'name');
                asort($subcategoryArray, SORT_STRING);
            }
            
            $renderArray['dataArray'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $subcategoryArray ?? []);
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array SubcategoryModel свойству SubcategoryOptionWidget::subcategory
     * @param array SubcategoryModel $subcategory
     */
    public function setSubcategoryArray(array $subcategoryArray)
    {
        try {
            $this->subcategoryArray = $subcategoryArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству SubcategoryOptionWidget::template
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

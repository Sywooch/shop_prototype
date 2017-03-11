<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Html;
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы поиска
 */
class SearchBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var string искомая фраза
     */
    private $text;
    /**
     * @var bool нужно ли экранировать label
     */
    public $encodeLabels = false;
    
    public function init()
    {
        try {
            $text = !empty($this->text) ? Html::tag('strong', $this->text) : '';
            
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Searching results {placeholder}', ['placeholder'=>$text])];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает фразу свойству AccountChangeDataWidget::text
     * @param string $text
     */
    public function setText(string $text)
    {
        try {
            $this->text = $text;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

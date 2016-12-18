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
    public $text;
    
    public function init()
    {
        try {
            $text = !empty($this->text) ? ' ' . Html::tag('strong', Html::encode($this->text)) : '';
            
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Searching results') . $text];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

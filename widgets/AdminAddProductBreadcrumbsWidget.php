<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы аккаунта
 */
class AdminAddProductBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    public function init()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Basic data'), 'url'=>['/admin/index']];
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Products'), 'url'=>['/admin/products']];
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Add product'), 'url'=>['/admin/add-product']];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

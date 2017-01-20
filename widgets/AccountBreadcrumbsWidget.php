<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы аутентификации
 */
class AccountBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    public function init()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = [
                'label'=>\Yii::t('base', 'Basic account data'), 
                'url'=>['/account/index']
            ];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

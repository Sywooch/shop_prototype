<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\helpers\Url;
use app\exceptions\ExceptionsTrait;

/**
 * Проверяет запрос перед вызовом ProductsListController::search
 * возвращает редирект на главную, если пуст $_GET['search']
 */
class SearchActionFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    public function beforeAction($action)
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return \Yii::$app->response->redirect(Url::to(['/products-list/index']))->send();
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

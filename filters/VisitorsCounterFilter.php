<?php

namespace app\filters;

use yii\base\ActionFilter;
use app\exceptions\ExceptionsTrait;
use app\helpers\{DateHelper,
    SessionHelper};
use app\services\VisitorsCounterGetSaveDateService;

/**
 * Фиксирует кол-во посетителей сайта посетителей
 */
class VisitorsCounterFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    public function beforeAction($action)
    {
        try {
            $timer = SessionHelper::read(\Yii::$app->params['visitorTimer']);
            
            if (empty($timer) || ((time() - $timer) > (60 * 30))) {
                $service = \Yii::$app->registry->get(VisitorsCounterGetSaveDateService::class, [
                    'date'=>DateHelper::getToday00()
                ]);
                $result = $service->get();
            }
            
            SessionHelper::write(\Yii::$app->params['visitorTimer'], time());
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

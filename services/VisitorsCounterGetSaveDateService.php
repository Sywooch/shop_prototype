<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\VisitorsCounterModel;
use app\finders\VisitorsCounterDateFinder;
use app\savers\ModelSaver;
use app\helpers\DateHelper;

/**
 * Возвращает объект VisitorsCounterModel, 
 * при необходимости создает  и сохраняет новый
 */
class VisitorsCounterGetSaveDateService extends AbstractBaseService
{
    /**
     * Возвращает VisitorsCounterModel по date
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return bool
     */
    public function handle($request=null): bool
    {
        try {
            $date = DateHelper::getToday00();
            
            $finder = \Yii::$app->registry->get(VisitorsCounterDateFinder::class, ['date'=>$date]);
            $visitorsCounterModel = $finder->find();
            
            if ($visitorsCounterModel === null) {
                $visitorsCounterModel = new VisitorsCounterModel();
                $visitorsCounterModel->date = $date;
            }
            
            $visitorsCounterModel->scenario = VisitorsCounterModel::SAVE;
            $visitorsCounterModel->counter++;
            
            if ($visitorsCounterModel->validate() === false) {
                throw new ErrorException($this->modelError($visitorsCounterModel->errors));
            }
            
            $saver = new ModelSaver([
                   'model'=>$visitorsCounterModel
            ]);
            $saver->save();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
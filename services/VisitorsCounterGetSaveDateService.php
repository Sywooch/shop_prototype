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
     * @var int текущая дата Unix Timestamp
     */
    private $date;
    
    /**
     * Возвращает VisitorsCounterModel по date
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @return bool
     */
    public function get(): bool
    {
        try {
            if (empty($this->date)) {
                throw new ErrorException($this->emptyError('date'));
            }
            
            //$date = DateHelper::getToday00();
            
            $finder = \Yii::$app->registry->get(VisitorsCounterDateFinder::class, [
                'date'=>$this->date
            ]);
            $visitorsCounterModel = $finder->find();
            
            if ($visitorsCounterModel === null) {
                $visitorsCounterModel = new VisitorsCounterModel();
                $visitorsCounterModel->date = $this->date;
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
    
    /**
     * Присваивает значение VisitorsCounterGetSaveDateService::date
     * @param int Unix Timestamp $date
     */
    public function setDate(int $date)
    {
        try {
            $this->date = $date;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\{ErrorException,
    Model};
use app\services\AbstractBaseService;
use app\models\CurrencyModel;
use app\updaters\CurrencyModelUpdater;
use app\finders\MainCurrencyFinder;
use app\helpers\CurrencyHelper;

/**
 * 
 * Проверяет актуальность курса для валюты,
 * при необходимости обновляет данные
 */
class CurrenyUpdateService extends AbstractBaseService
{
    /**
     * @var Model
     */
    private $updateCurrencyModel;
    
    /**
     * Возвращает объект CurrencyModel
     * @return CurrencyModel
     */
    public function get(): Model
    {
        try {
            if (empty($this->updateCurrencyModel)) {
                throw new ErrorException($this->emptyError('updateCurrencyModel'));
            }
            
            if (empty($this->updateCurrencyModel->update_date) || (time() - $this->updateCurrencyModel->update_date > (60 * 60))) {
                $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                $baseCurrencyModel = $finder->find();
                if (empty($baseCurrencyModel)) {
                    throw new ErrorException($this->emptyError('baseCurrencyModel'));
                }
                
                $exchange_rate = CurrencyHelper::exchangeRate($baseCurrencyModel->code, $this->updateCurrencyModel->code);
                
                $this->updateCurrencyModel->scenario = CurrencyModel::UPDATE;
                $this->updateCurrencyModel->exchange_rate = $exchange_rate;
                $this->updateCurrencyModel->update_date = time();
                if ($this->updateCurrencyModel->validate() === false) {
                    throw new ErrorException($this->modelError($this->updateCurrencyModel->errors));
                }
                
                $updater = new CurrencyModelUpdater([
                    'model'=>$this->updateCurrencyModel
                ]);
                $updater->update();
            }
            
            return $this->updateCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CurrenyUpdateService::updateCurrencyModel
     * @param Model $updateCurrencyModel
     */
    public function setUpdateCurrencyModel(Model $updateCurrencyModel)
    {
        try {
            $this->updateCurrencyModel = $updateCurrencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

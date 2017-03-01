<?php

namespace app\services;

use yii\base\{ErrorException,
    Model};
use app\services\AbstractBaseService;
use app\models\CurrencyModel;
use app\savers\ModelSaver;
use app\finders\MainCurrencyFinder;

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
            
            if (time() - $this->updateCurrencyModel->update_date > (60 * 60)) {
                $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                $mainCurrencyModel = $finder->find();
                if (empty($mainCurrencyModel)) {
                    throw new ErrorException($this->emptyError('mainCurrencyModel'));
                }
                
                $mainCurrenyCode = $mainCurrencyModel->code;
                $updateCurrencyCode = $this->updateCurrencyModel->code;
                
                $dataJSON = file_get_contents('http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+in+("' . $mainCurrenyCode . $updateCurrencyCode . '")&format=json&env=store://datatables.org/alltableswithkeys');
                if (empty($dataJSON)) {
                    throw new ErrorException($this->emptyError('dataJSON'));
                }
                
                $currencyDataObject = json_decode($dataJSON);
                if (empty($currencyDataObject)) {
                    throw new ErrorException($this->emptyError('currencyDataObject'));
                }
                
                $exchange_rate = $currencyDataObject->query->results->rate->Rate;
                if (empty($exchange_rate)) {
                    throw new ErrorException($this->emptyError('exchange_rate'));
                }
                
                $this->updateCurrencyModel->scenario = CurrencyModel::UPDATE;
                $this->updateCurrencyModel->exchange_rate = $exchange_rate;
                $this->updateCurrencyModel->update_date = time();
                if ($this->updateCurrencyModel->validate() === false) {
                    throw new ErrorException($this->modelError($this->updateCurrencyModel->errors));
                }
                
                $saver = new ModelSaver([
                    'model'=>$this->updateCurrencyModel
                ]);
                $saver->save();
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

<?php

namespace app\services;

use yii\base\{ErrorException,
    Model};
use app\services\{AbstractBaseService,
    CurrencyUpdateService};
use app\savers\SessionModelSaver;
use app\finders\{CurrencySessionDBMSFinder,
    MainCurrencyFinder};
use app\models\CurrencyModel;

/**
 * Возвращает CurrencyModel текущей валюты
 * Первый запрос отправляет в сессию, 
 * если данных нет, в СУБД и сохраняет полученные данные в сессию
 */
class GetCurrentCurrencyModelService extends AbstractBaseService
{
    /**
     * @var string ключ
     */
    private $key;
    /**
     * @var CurrencyModel
     */
    private $currencyModel = null;
    
    /**
     * Возвращает объект текущей валюты
     * @return CurrencyModel
     */
    public function get(): CurrencyModel
    {
        try {
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            
            if (empty($this->currencyModel)) {
                $finder = \Yii::$app->registry->get(CurrencySessionDBMSFinder::class, [
                    'key'=>$this->key
                ]);
                $currencyModel = $finder->find();
                
                if (empty($currencyModel)) {
                    $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                    $currencyModel = $finder->find();
                    if (empty($currencyModel)) {
                        throw new ErrorException($this->emptyError('currencyModel'));
                    }
                    
                    $rawCurrencyModel = new CurrencyModel(['scenario'=>CurrencyModel::SESSION]);
                    $rawCurrencyModel->id = $currencyModel->id;
                    if ($rawCurrencyModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawCurrencyModel->errors));
                    }
                    
                    $saver = new SessionModelSaver([
                        'key'=>$this->key,
                        'model'=>$rawCurrencyModel
                    ]);
                    $saver->save();
                }
                
                $currencyModel = $this->updateCurrency($currencyModel);
                
                $this->currencyModel = $currencyModel;
            }
            
            return $this->currencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ключ GetCurrentCurrencyModelService::key
     * @param string $key
     */
    public function setKey(string $key)
    {
        try {
            $this->key = $key;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Обновляет данные валюты
     * @param Model $currencyModel
     */
    private function updateCurrency(Model $currencyModel): Model
    {
        try {
            $service = \Yii::$app->registry->get(CurrencyUpdateService::class, [
                'updateCurrencyModel'=>$currencyModel,
            ]);
            $currencyModel = $service->get();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            return $currencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

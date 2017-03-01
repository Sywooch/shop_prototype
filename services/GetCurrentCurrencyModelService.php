<?php

namespace app\services;

use yii\base\{ErrorException,
    Model};
use app\services\AbstractBaseService;
use app\savers\SessionModelSaver;
use app\finders\{CurrencySessionFinder,
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
                $finder = \Yii::$app->registry->get(CurrencySessionFinder::class, [
                    'key'=>$this->key
                ]);
                $currencyModel = $finder->find();
                
                if (!empty($currencyModel)) {
                    $currencyModel = $this->updateCurrency($currencyModel);
                } else {
                    $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                    $currencyModel = $finder->find();
                    if (empty($currencyModel)) {
                        throw new ErrorException($this->emptyError('currencyModel'));
                    }
                    
                    $currencyModel = $this->updateCurrency($currencyModel);
                    
                    $saver = new SessionModelSaver([
                        'key'=>$this->key,
                        'model'=>$currencyModel
                    ]);
                    $saver->save();
                }
                
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
            $service = \Yii::$app->registry->get(CurrenyUpdateService::class, [
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

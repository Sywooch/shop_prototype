<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\finders\{CurrencySessionFinder,
    MainCurrencyFinder};
use app\models\CurrencyModel;

/**
 * Возвращает объект текущей валюты
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
     * Возвращает CurrencyModel текущей валюты
     * Первый запрос отправляет в сессию, 
     * если данных нет, в СУБД и сохраняет полученные данные в сессию
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
                
                if (empty($currencyModel)) {
                    $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                    $currencyModel = $finder->find();
                    if (empty($currencyModel)) {
                        throw new ErrorException($this->emptyError('currencyModel'));
                    }
                    
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
}

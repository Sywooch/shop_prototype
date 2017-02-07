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
            if (empty($this->currencyModel)) {
                $key = HashHelper::createCurrencyKey();
                
                $finder = \Yii::$app->registry->get(CurrencySessionFinder::class, ['key'=>$key]);
                $currencyModel = $finder->find();
                
                if (empty($currencyModel)) {
                    $finder = \Yii::$app->registry->get(MainCurrencyFinder::class);
                    $currencyModel = $finder->find();
                    if (empty($currencyModel)) {
                        throw new ErrorException($this->emptyError('currencyModel'));
                    }
                    
                    $saver = new SessionModelSaver([
                        'key'=>$key,
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
}

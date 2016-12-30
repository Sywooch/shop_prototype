<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\helpers\HashHelper;
use app\savers\SessionSaver;
use app\finders\{CurrencySessionFinder,
    MainCurrencyFinder};
use app\models\CurrencyModel;

/**
 * Возвращает объект текущей валюты
 */
class CurrentCurrencyService extends AbstractBaseService
{
    /**
     * Возвращает данные текущей валюты
     * Первый запрос отправляет в сессию, 
     * если данных нет, в СУБД и сохраняет полученные данные в сессию
     * @return CurrencyModel
     */
    public function handle($request=null): CurrencyModel
    {
        try {
            $key = HashHelper::createCurrencyKey();
            
            $finder = \Yii::$app->registry->get(CurrencySessionFinder::class, ['key'=>$key]);
            $currencyModel = $finder->find();
            
            if (empty($currencyModel)) {
                $finder = new MainCurrencyFinder();
                $currencyModel = $finder->find();
                if (empty($currencyModel)) {
                    throw new ErrorException($this->emptyError('currencyModel'));
                }
                
                $saver = new SessionSaver([
                    'key'=>$key,
                    'models'=>[$currencyModel]
                ]);
                $saver->save();
            }
            
            return $currencyModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

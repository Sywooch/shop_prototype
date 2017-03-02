<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

/**
 * Коллекция методов для работы с валютой
 */
class CurrencyHelper
{
    /**
     * Возвращает курс валюты по отношению к базовой
     * @param string $baseCode код базовой валюты
     * @param string $targetCode код валюты, для которой необходим курс
     * @return float
     */
    public static function exchangeRate(string $baseCode, string $targetCode): float
    {
        try {
            $dataJSON = file_get_contents('http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+in+("' . $baseCode . $targetCode . '")&format=json&env=store://datatables.org/alltableswithkeys');
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
            
            return $exchange_rate;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

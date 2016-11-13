<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\CurrencyModel;
use app\interfaces\SearchFilterInterface;

class CurrencyFilter extends Model implements SearchFilterInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий поиска данных для виджета замены текущей валюты
     */
    const WIDGET_SEARCH = 'widgetSearch';
    
    /**
     * Принимает запрос на поиск данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function search(string $scenario, $data=null)
    {
        try {
            switch ($scenario) {
                case self::WIDGET_SEARCH:
                    return $this->widgetSearch();
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает сортированный массив CurrencyModel
     * @param array $data данные $_GET запроса 
     * @return array CurrencyModel
     */
    private function widgetSearch(): array
    {
        try {
            $currencyArray = CurrencyModel::find()->asArray()->all();
            $currencyArray = ArrayHelper::map($currencyArray, 'id', 'code');
            asort($currencyArray, SORT_STRING);
            
            return $currencyArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

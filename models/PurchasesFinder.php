<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\PurchasesModel;
use app\interfaces\FinderSearchInterface;

class PurchasesFinder extends Model implements FinderSearchInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий поиска данных для виджета корзины
     */
    const SEARCH_FOR_CART_WIDGET = 'searchForCartWidget';
    
    /**
     * Принимает запрос на поиск данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function search(string $scenario, $data)
    {
        try {
            switch ($scenario) {
                case self::SEARCH_FOR_CART_WIDGET:
                    return $this->searchForCartWidget($data);
                    break;
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Подготавливает данные для виджета корзины
     * @param mixed $data данные $_POST запроса 
     * @return array
     */
    private function searchForCartWidget($data): array
    {
        try {
            $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($model->load($data) && $model->validate()) {
                $result = ArrayHelper::merge($model->toArray(), ['price'=>$model->product->price]);
            }
            
            return $result ?? [];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

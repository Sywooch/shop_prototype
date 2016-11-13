<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\PurchasesModel;
use app\interfaces\{SaveFilterInterface,
    SearchFilterInterface};
use app\helpers\{HashHelper,
    SessionHelper};

class PurchasesFilter extends Model implements SaveFilterInterface, SearchFilterInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий поиска данных в сессии
     */
    const SESSION_SEARCH = 'sessionSearch';
    /**
     * Сценарий сохранения данных в сессии
     */
    const SESSION_SAVE = 'sessionSave';
    
    /**
     * Принимает запрос на поиск данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function search(string $scenario, $data=null)
    {
        try {
            switch ($scenario) {
                case self::SESSION_SEARCH:
                    return $this->sessionSearch();
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Принимает запрос на сохранение данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function save(string $scenario, $data=null)
    {
        try {
            switch ($scenario) {
                case self::SESSION_SAVE:
                    return $this->sessionSave($data);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные о покупке, сохраненные в сессии
     * @param mixed $data данные $_POST запроса 
     * @return array
     */
    private function sessionSearch(): array
    {
        try {
            $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $cartArray = SessionHelper::read($cartKey);
            
            return !empty($cartArray) ? $cartArray : [];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет данные о покупке в сессии
     * @param mixed $data данные $_POST запроса 
     * @return bool
     */
    private function sessionSave($data): bool
    {
        try {
            $model = new PurchasesModel(['scenario'=>PurchasesModel::GET_FROM_ADD_TO_CART]);
            
            if ($model->load($data) && $model->validate()) {
                $purchase = $model->toArray();
                $id = $purchase['id_product'];
                
                $purchasesArray = $this->sessionSearch();
                
                if (array_key_exists($id, $purchasesArray)) {
                    $purchasesArray[$id]['quantity'] += $purchase['quantity'];
                } else {
                    $purchasesArray[$id] = $purchase;
                }
            
                $cartKey = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
                SessionHelper::write($cartKey, $purchasesArray);
                
                return true;
            }
            
            return false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

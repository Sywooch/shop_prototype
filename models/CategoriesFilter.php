<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\models\CategoriesModel;
use app\interfaces\SearchFilterInterface;

class CategoriesFilter extends Model implements SearchFilterInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий поиска данных для виджета меню
     */
    const MENU_SEARCH = 'menuSearch';
    
    /**
     * Принимает запрос на поиск данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function search(string $scenario, $data=null)
    {
        try {
            switch ($scenario) {
                case self::MENU_SEARCH:
                    return $this->menuSearch();
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает сортированный массив CategoriesModel
     * @param array $data данные $_GET запроса 
     * @return array CategoriesModel
     */
    private function menuSearch(): array
    {
        try {
            $categoriesArray = CategoriesModel::find()->with('subcategory')->all();
            ArrayHelper::multisort($categoriesArray, 'name', SORT_ASC);
            
            return $categoriesArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

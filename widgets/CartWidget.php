<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\interfaces\SearchFilterInterface;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object BaseFIltersInterface для поиска данных по запросу
     */
    private $filterClass;
    /**
     * @var string сценарий поиска
     */
    public $filterScenario;
    /**
     * @var string имя шаблона
     */
    public $view;
    /**
     * @var int общее количество товаров в корзине
     */
    private $goods = 0;
    /**
     * @var int общая стоимость товаров в корзине
     */
    private $cost = 0;
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            $purchasesArray = $this->filterClass->search($this->filterScenario);
            
            if (!empty($purchasesArray)) {
                foreach ($purchasesArray as $purchase) {
                    $this->goods += $purchase['quantity'];
                    $this->cost += ($purchase['price'] * $purchase['quantity']);
                }
            }
            
            if (!empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                $this->cost = \Yii::$app->formatter->asDecimal($this->cost * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
            }
            
            return $this->render($this->view, ['goods'=>$this->goods, 'cost'=>$this->cost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setFilterClass(SearchFilterInterface $filterClass)
    {
        try {
            $this->filterClass = $filterClass;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

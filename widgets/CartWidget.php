<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\repository\{GetGroupRepositoryInterface,
    GetOneRepositoryInterface};
use app\helpers\HashHelper;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object GetGroupRepositoryInterface для поиска данных по запросу
     */
    private $repository;
    /**
     * @var object GetOneRepositoryInterface для поиска данных по запросу
     */
    private $currency;
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
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $purchasesArray = $this->repository->getGroup($key);
            
            if (!empty($purchasesArray)) {
                foreach ($purchasesArray as $purchase) {
                    $this->goods += $purchase->quantity;
                    $this->cost += ($purchase->price * $purchase->quantity);
                }
            }
            
            $currency = $this->currency->getOne(\Yii::$app->params['currencyKey']);
            if (!empty($currency->exchange_rate) && !empty($currency->code)) {
                $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $currency->exchange_rate, 2) . ' ' . $currency->code;
            }
            
            return $this->render($this->view, ['goods'=>$this->goods, 'cost'=>$this->cost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setRepository(GetGroupRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function setCurrency(GetOneRepositoryInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

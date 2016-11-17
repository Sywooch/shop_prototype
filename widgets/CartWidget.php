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
    private $currencyRepository;
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
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$this->repository'));
            }
            if (empty($this->currencyRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$this->currencyRepository'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('$this->view'));
            }
            
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $purchasesArray = $this->repository->getGroup($key);
            
            if (!empty($purchasesArray)) {
                foreach ($purchasesArray as $purchase) {
                    $this->goods += $purchase->quantity;
                    $this->cost += ($purchase->price * $purchase->quantity);
                }
            }
            
            $currency = $this->currencyRepository->getOne(\Yii::$app->params['currencyKey']);
            if (!empty($currency->exchange_rate) && !empty($currency->code)) {
                $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $currency->exchange_rate, 2) . ' ' . $currency->code;
            }
            
            return $this->render($this->view, ['goods'=>$this->goods, 'cost'=>$this->cost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает GetGroupRepositoryInterface свойству CartWidget::repository
     * @param object $repository GetGroupRepositoryInterface
     */
    public function setRepository(GetGroupRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает GetOneRepositoryInterface свойству CartWidget::currencyRepository
     * @param object $currencyRepository GetOneRepositoryInterface
     */
    public function setCurrencyRepository (GetOneRepositoryInterface $currencyRepository)
    {
        try {
            $this->currencyRepository = $currencyRepository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

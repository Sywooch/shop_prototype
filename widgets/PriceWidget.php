<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\repository\RepositoryInterface;

/**
 * Формирует HTML строку с данными о цене товара
 */
class PriceWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface для поиска данных по запросу
     */
    private $repository;
    /**
     * @var float цена товара
     */
    public $price;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->price)) {
                throw new ErrorException(ExceptionsTrait::emptyError('price'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Форматирует стоимость с учетом текущей валюты
     * @return string
     */
    public function run()
    {
        try {
            $currency = $this->repository->getOne(\Yii::$app->params['currencyKey']);
            
            if (!empty($this->price) && !empty($currency->exchange_rate) && !empty($currency->code)) {
                $correctedPrice = \Yii::$app->formatter->asDecimal($this->price * $currency->exchange_rate, 2) . ' ' . $currency->code;
            }
            
            return $correctedPrice ?? '';
        } catch(\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству PriceWidget::repository
     * @param object $repository RepositoryInterface
     */
    public function setRepository(RepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

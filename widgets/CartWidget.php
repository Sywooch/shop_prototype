<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var string имя класса для построения запроса
     */
    public $modelClass = ProductsModel::class;
    /**
     * @var array имена полей для форматирования результата в виде $key=>$value
     */
    public $map = ['key'=>'id', 'value'=>'price'];
    /**
     * @var string имя HTML шаблона
     */
    public $view = 'short-cart.twig';
    /**
     * @var int количество товаров в корзине
     */
    private $_productsCount = 0;
    /**
     * @var float общая стоимость товаров в корзине
     */
    private $_totalCost = 0;
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            if (!empty(\Yii::$app->params['cartArray'])) {
                $productsArray = $this->modelClass::find()->addCriteria()->map($this->map['key'], $this->map['value'])->all();
                
                foreach (\Yii::$app->params['cartArray'] as $purchase) {
                    $this->_productsCount += $purchase['quantity'];
                    $this->_totalCost += ($productsArray[$purchase['id_product']] * $purchase['quantity']);
                }
            }
            
            if (!empty(\Yii::$app->currency->exchange_rate) && !empty(\Yii::$app->currency->code)) {
                $this->_totalCost = \Yii::$app->formatter->asDecimal($this->_totalCost * \Yii::$app->currency->exchange_rate, 2) . ' ' . \Yii::$app->currency->code;
            }
            
            return $this->render($this->view, ['productsCount'=>$this->_productsCount, 'totalCost'=>$this->_totalCost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

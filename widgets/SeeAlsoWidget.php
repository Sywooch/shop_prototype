<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;
use app\widgets\PriceWidget;

/**
 * Формирует HTML строку с информацией о похожих товарах
 */
class SeeAlsoWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var array ProductsModel
     */
    public $data;
    /**
     * @var string текст заголовка
     */
    public $text;
    /**
     * @var array массив результирующих строк
     */
    private $_result = [];
    
    /**
     * Конструирует HTML строку с информацией о похожих товарах
     * @return string
     */
    public function run()
    {
        try {
            if (!empty($this->data)) {
                if (!empty($this->text)) {
                    $this->_result[] = Html::tag('p', Html::tag('strong', $this->text));
                }
                
                $itemsArray = [];
                foreach($this->data as $model) {
                    $a = Html::a($model->name, Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$model->seocode]));
                    $price = implode(' ', [\Yii::t('base', 'Price:'), PriceWidget::widget(['price'=>$model->price])]);
                    $itemsArray[] = Html::tag('li', implode('<br/>', [$a, $price]));
                }
                $this->_result[] = Html::tag('ul', implode('', $itemsArray));
            }
            
            return !empty($this->_result) ? implode('', $this->_result) : '';
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

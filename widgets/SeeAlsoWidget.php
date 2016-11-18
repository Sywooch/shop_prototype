<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use yii\helpers\{Html,
    Url};
use app\exceptions\ExceptionsTrait;
use app\widgets\PriceWidget;
use app\repository\{CurrencySessionRepository,
    GetGroupRepositoryInterface,
    GetOneRepositoryInterface};
use app\models\ProductsModel;

/**
 * Формирует HTML строку с информацией о похожих товарах
 */
class SeeAlsoWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object GetGroupRepositoryInterface для поиска данных по запросу
     */
    private $repository;
     /**
     * @var object ProductsModel
     */
    private $model;
    /**
     * @var string текст заголовка
     */
    public $text;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->model)) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            if (empty($this->text)) {
                throw new ErrorException(ExceptionsTrait::emptyError('text'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML строку с информацией о похожих товарах
     * @return string
     */
    public function run()
    {
        try {
            $modelsArray = $this->repository->getGroup($this->model);
            
            if (!empty($modelsArray)) {
                $itemsArray = [];
                foreach($modelsArray as $model) {
                    $link = Html::a($model->name, Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$model->seocode]));
                    $price = PriceWidget::widget(['repository'=>new CurrencySessionRepository(), 'price'=>$model->price]);
                    $itemsArray['products'][] = ['link'=>$link, 'price'=>$price];
                }
            }
            
            return !empty($itemsArray) ? $this->render($this->view, array_merge(['text'=>$this->text], $itemsArray)) : '';
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает GetGroupRepositoryInterface свойству SeeAlsoWidget::repository
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
     * Присваивает ProductsModel свойству SeeAlsoWidget::model
     * @param object $model ProductsModel
     */
    public function setModel(ProductsModel $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

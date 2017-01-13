<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;
use app\forms\PurchaseForm;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends AbstractBaseWidget
{
    /**
     * @var object PurchasesCollection
     */
    private $purchases;
    /**
     * @var CurrencyModel
     */
    private $currency;
    /**
     * @var object PurchaseForm
     */
    private $updateForm;
    /**
     * @var object PurchaseForm
     */
    private $deleteForm;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->purchases) || $this->purchases->isEmpty() === true) {
                throw new ErrorException($this->emptyError('purchases'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->updateForm)) {
                throw new ErrorException($this->emptyError('updateForm'));
            }
            if (empty($this->deleteForm)) {
                throw new ErrorException($this->emptyError('deleteForm'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            foreach ($this->purchases as $purchase) {
                $set = [];
                $set['id_product'] = $purchase->id_product;
                $set['link'] = Html::a(Html::encode($purchase->product->name), ['/product-detail/index', 'seocode'=>$purchase->product->seocode]);
                $set['short_description'] = $purchase->product->short_description;
                $set['price'] = \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code;
                
                $updateForm = clone $this->updateForm;
                
                $set['formModel'] = \Yii::configure($updateForm, [
                    'id_color'=>$purchase->id_color,
                    'id_size'=>$purchase->id_size,
                    'quantity'=>$purchase->quantity,
                ]);
                $set['idForm'] = sprintf('update-product-form-%d', $purchase->id_product);
                
                $set['formModelDelete'] = clone $this->deleteForm;
                $set['idFormDelete'] = sprintf('delete-product-form-%d', $purchase->id_product);
                
                $colors = $purchase->product->colors;
                ArrayHelper::multisort($colors, 'color');
                $set['colors'] = ArrayHelper::map($colors, 'id', 'color');
                
                $sizes = $purchase->product->sizes;
                ArrayHelper::multisort($sizes, 'size');
                $set['sizes'] = ArrayHelper::map($sizes, 'id', 'size');
                
                if (!empty($purchase->product->images)) {
                    $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $purchase->product->images) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    if (!empty($imagesArray)) {
                        $set['image'] = Html::img(\Yii::getAlias('@imagesweb/' . $purchase->product->images . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]), ['height'=>200]);
                    }
                }
                
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['header'] = \Yii::t('base', 'Selected products');
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/cart/update']);
            $renderArray['button'] = \Yii::t('base', 'Update');
            
            $renderArray['formActionDelete'] = Url::to(['/cart/delete']);
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollection свойству CartWidget::purchases
     * @param object $collection PurchasesCollection
     */
    public function setPurchases(PurchasesCollection $collection)
    {
        try {
            $this->purchases = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству CartWidget::currency
     * @param CurrencyModel $model
     */
    public function setCurrency(CurrencyModel $model)
    {
        try {
            $this->currency = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchaseForm свойству CartWidget::updateForm
     * @param CommentForm $form
     */
    public function setUpdateForm(PurchaseForm $updateForm)
    {
        try {
            $this->updateForm = $updateForm;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchaseForm свойству CartWidget::deleteForm
     * @param CommentForm $form
     */
    public function setDeleteForm(PurchaseForm $deleteForm)
    {
        try {
            $this->deleteForm = $deleteForm;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

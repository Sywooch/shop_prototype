<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyInterface;
use app\forms\AbstractBaseForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends AbstractBaseWidget
{
    /**
     * @var object PurchasesCollectionInterface
     */
    private $purchases;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var object PurchaseForm
     */
    private $form;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
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
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            foreach ($this->purchases as $purchase) {
                $set = [];
                $set['id_product'] = $purchase->id_product;
                $set['link'] = Html::a(Html::encode($purchase->product->name), ['/product-detail/index', 'seocode'=>$purchase->product->seocode]);
                $set['short_description'] = $purchase->product->short_description;
                $set['price'] = \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2) . ' ' . $this->currency->code();
                
                $updateForm = clone $this->form;
                
                $set['formModel'] = \Yii::configure($updateForm, [
                    'id_color'=>$purchase->id_color,
                    'id_size'=>$purchase->id_size,
                    'quantity'=>$purchase->quantity,
                ]);
                $set['idForm'] = sprintf('update-product-form-%d', $purchase->id_product);
                
                $set['formModelDelete'] = clone $this->form;
                $set['idFormDelete'] = sprintf('delete-product-form-%d', $purchase->id_product);
                
                $colors = $purchase->product->colors;
                ArrayHelper::multisort($colors, 'color');
                $set['colors'] = ArrayHelper::map($colors, 'id', 'color');
                
                $sizes = $purchase->product->sizes;
                ArrayHelper::multisort($sizes, 'size');
                $set['sizes'] = ArrayHelper::map($sizes, 'id', 'size');
                
                if (!empty($purchase->product->images)) {
                    $set['image'] = ImgHelper::randThumbn($purchase->product->images);
                }
                
                $renderArray['collection'][] = $set;
            }
            
            $renderArray['header'] = $this->header;
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/cart/update']);
            $renderArray['button'] = \Yii::t('base', 'Update');
            
            $renderArray['formActionDelete'] = Url::to(['/cart/delete']);
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchasesCollectionInterface свойству CartWidget::purchases
     * @param object $collection PurchasesCollectionInterface
     */
    public function setPurchases(PurchasesCollectionInterface $collection)
    {
        try {
            $this->purchases = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству CartWidget::currency
     * @param CurrencyInterface $currency
     */
    public function setCurrency(CurrencyInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CartWidget::form
     * @param CommentForm $form
     */
    public function setForm(AbstractBaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству CartWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству CartWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

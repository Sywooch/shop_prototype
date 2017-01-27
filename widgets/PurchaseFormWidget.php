<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;
use yii\helpers\{ArrayHelper,
    Url};
use app\models\ProductsModel;
use app\forms\PurchaseForm;

/**
 * Формирует HTML строку с тегами img
 */
class PurchaseFormWidget extends AbstractBaseWidget
{
    /**
     * @var ProductsModel
     */
    private $product;
    /**
     * @var PurchaseForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->product)) {
                throw new ErrorException($this->emptyError('product'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['formId'] = 'purchase-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/cart/add']);
            $renderArray['button'] = \Yii::t('base', 'Add to cart');
            
            $renderArray['formModel'] = $this->form;
            $renderArray['id'] = $this->product->id;
            $renderArray['price'] = $this->product->price;
            
            $colors = $this->product->colors;
            ArrayHelper::multisort($colors, 'color');
            $renderArray['colors'] = ArrayHelper::map($colors, 'id', 'color');
            
            $sizes = $this->product->sizes;
            ArrayHelper::multisort($sizes, 'size');
            $renderArray['sizes'] = ArrayHelper::map($sizes, 'id', 'size');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel свойству PurchaseFormWidget::model
     * @param ProductsModel $model
     */
    public function setProduct(ProductsModel $product)
    {
        try {
            $this->product = $product;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает PurchaseForm свойству PurchaseFormWidget::form
     * @param PurchaseForm $model
     */
    public function setForm(PurchaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству PurchaseFormWidget::template
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

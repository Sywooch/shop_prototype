<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\forms\AbstractBaseForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с данными товара
 */
class AdminProductDataWidget extends AbstractBaseWidget
{
    /**
     * @var Model
     */
    private $productsModel;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var AbstractBaseForm
     */
    private $form;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->productsModel)) {
                throw new ErrorException($this->emptyError('productsModel'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray = [];
            $renderArray['id'] = $this->productsModel->id;
            $renderArray['date'] = \Yii::$app->formatter->asDate($this->productsModel->date);
            $renderArray['code'] = $this->productsModel->code;
            $renderArray['link'] = Url::to(['/product-detail/index', 'seocode'=>$this->productsModel->seocode]);
            $renderArray['linkText'] = Html::encode($this->productsModel->name);
            $renderArray['short_description'] = Html::encode($this->productsModel->short_description);
            $renderArray['description'] = Html::encode($this->productsModel->description);
            $renderArray['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($this->productsModel->price * $this->currency->exchangeRate(), 2), $this->currency->code());
            $renderArray['colors'] = implode(', ', ArrayHelper::getColumn($this->productsModel->colors, 'color'));
            $renderArray['sizes'] = implode(', ', ArrayHelper::getColumn($this->productsModel->sizes, 'size'));
            if (!empty($this->productsModel->images)) {
                $renderArray['image'] = ImgHelper::randThumbn($this->productsModel->images);
            }
            $renderArray['category'] = $this->productsModel->category->name;
            $renderArray['subcategory'] = $this->productsModel->subcategory->name;
            $renderArray['brand'] = $this->productsModel->brand->brand;
            $renderArray['active'] = $this->productsModel->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
            $renderArray['total_products'] = $this->productsModel->total_products;
            $renderArray['seocode'] = $this->productsModel->seocode;
            $renderArray['views'] = $this->productsModel->views;
            
            $renderArray['modelForm'] = \Yii::configure($this->form, ['id'=>$this->productsModel->id]);
            
            $renderArray['formId'] = sprintf('admin-product-detail-get-form-%d', $this->productsModel->id);
            $renderArray['formAction'] = Url::to(['/admin/product-detail-form']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
            $renderArray['formIdDelete'] = sprintf('admin-product-detail-delete-form-%d', $this->productsModel->id);
            $renderArray['formActionDelete'] = Url::to(['/admin/product-detail-delete']);
            $renderArray['buttonDelete'] = \Yii::t('base', 'Delete');
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['idHeader'] = \Yii::t('base', 'Product Id');
            $renderArray['dateHeader'] = \Yii::t('base', 'Date added');
            $renderArray['codeHeader'] = \Yii::t('base', 'Code');
            $renderArray['shortDescriptionHeader'] = \Yii::t('base', 'Short description');
            $renderArray['descriptionHeader'] = \Yii::t('base', 'Description');
            $renderArray['priceHeader'] = \Yii::t('base', 'Price');
            $renderArray['colorsHeader'] = \Yii::t('base', 'Colors');
            $renderArray['sizesHeader'] = \Yii::t('base', 'Sizes');
            $renderArray['categoryHeader'] = \Yii::t('base', 'Category');
            $renderArray['subcategoryHeader'] = \Yii::t('base', 'Subcategory');
            $renderArray['brandHeader'] = \Yii::t('base', 'Brand');
            $renderArray['activeHeader'] = \Yii::t('base', 'Active');
            $renderArray['totalProductsHeader'] = \Yii::t('base', 'Total products');
            $renderArray['seocodeHeader'] = \Yii::t('base', 'Seocode');
            $renderArray['viewsHeader'] = \Yii::t('base', 'Views');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству AdminProductDataWidget::productsModel
     * @param Model $productsModel
     */
    public function setProductsModel(Model $productsModel)
    {
        try {
            $this->productsModel = $productsModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение свойству AdminProductDataWidget::currency
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
     * Присваивает значение свойству AdminProductDataWidget::form
     * @param AbstractBaseForm $form
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
     * Присваивает имя шаблона свойству AdminOrderDataWidget::template
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

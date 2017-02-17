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
 * Формирует HTML строку с каталогом товаров
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
            
            $renderArray = [];
            
            $renderArray = [];
            $renderArray['id'] = $this->productsMode->id;
            $renderArray['date'] = \Yii::$app->formatter->asDate($this->productsMode->date);
            $renderArray['code'] = $this->productsMode->code;
            $renderArray['link'] = Url::to(['/product-detail/index', 'seocode'=>$this->productsMode->seocode]);
            $renderArray['linkText'] = Html::encode($this->productsMode->name);
            $renderArray['short_description'] = Html::encode($this->productsMode->short_description);
            $renderArray['description'] = Html::encode($this->productsMode->description);
            $renderArray['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($this->productsMode->price * $this->currency->exchangeRate(), 2), $this->currency->code());
            $renderArray['colors'] = implode(', ', ArrayHelper::getColumn($this->productsMode->colors, 'color'));
            $renderArray['sizes'] = implode(', ', ArrayHelper::getColumn($this->productsMode->sizes, 'size'));
            if (!empty($this->productsMode->images)) {
                $renderArray['image'] = ImgHelper::randThumbn($this->productsMode->images);
            }
            $renderArray['category'] = $this->productsMode->category->name;
            $renderArray['subcategory'] = $this->productsMode->subcategory->name;
            $renderArray['brand'] = $this->productsMode->brand->brand;
            $renderArray['active'] = $this->productsMode->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
            $renderArray['total_products'] = $this->productsMode->total_products;
            $renderArray['seocode'] = $this->productsMode->seocode;
            $renderArray['views'] = $this->productsMode->views;
            
            $renderArray['modelForm'] = \Yii::configure($this->form, ['id'=>$this->productsMode->id]);
            $renderArray['formId'] = sprintf('admin-product-detail-get-form-%d', $this->productsMode->id);
            $renderArray['formAction'] = Url::to(['/admin/product-detail-form']);
            $renderArray['button'] = \Yii::t('base', 'Change');
            
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
}

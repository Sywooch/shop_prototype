<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\forms\AdminProductForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с каталогом товаров
 */
class AdminProductsWidget extends AbstractBaseWidget
{
    /**
     * @var array ProductsModel
     */
    private $products;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var AdminProductForm
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
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
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
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->products)) {
                $renderArray['listClass'] = 'admin-products';
                
                foreach ($this->products as $product) {
                    $set = [];
                    $set['id'] = $product->id;
                    $set['date'] = \Yii::$app->formatter->asDate($product->date);
                    $set['code'] = $product->code;
                    $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$product->seocode]);
                    $set['linkText'] = Html::encode($product->name);
                    $set['short_description'] = Html::encode($product->short_description);
                    $set['description'] = Html::encode($product->description);
                    $set['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($product->price * $this->currency->exchangeRate(), 2), $this->currency->code());
                    $set['colors'] = implode(', ', ArrayHelper::getColumn($product->colors, 'color'));
                    $set['sizes'] = implode(', ', ArrayHelper::getColumn($product->sizes, 'size'));
                    if (!empty($product->images)) {
                        $set['image'] = ImgHelper::randThumbn($product->images);
                    }
                    $set['category'] = $product->category->name;
                    $set['subcategory'] = $product->subcategory->name;
                    $set['brand'] = $product->brand->brand;
                    $set['active'] = $product->active ? \Yii::t('base', 'Active') : \Yii::t('base', 'Not active');
                    $set['total_products'] = $product->total_products;
                    $set['seocode'] = $product->seocode;
                    $set['views'] = $product->views;
                    
                    $form = clone $this->form;
                    $set['modelForm'] = \Yii::configure($form, ['id'=>$product->id]);
                    
                    $set['formId'] = sprintf('admin-product-detail-get-form-%d', $product->id);
                    $set['formAction'] = Url::to(['/admin/product-detail-form']);
                    $set['button'] = \Yii::t('base', 'Change');
                    
                    $set['formIdDelete'] = sprintf('admin-product-detail-delete-form-%d', $product->id);
                    $set['formActionDelete'] = Url::to(['/admin/product-detail-delete']);
                    $set['buttonDelete'] = \Yii::t('base', 'Delete');
                    
                    $set['ajaxValidation'] = false;
                    $set['validateOnSubmit'] = false;
                    $set['validateOnChange'] = false;
                    $set['validateOnBlur'] = false;
                    $set['validateOnType'] = false;
                    
                    $renderArray['products'][] = $set;
                }
                
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
            } else {
                $renderArray['productsEmpty'] = \Yii::t('base', 'No products');
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ProductsModel свойству AdminProductsWidget::products
     * @param array $products
     */
    public function setProducts(array $products)
    {
        try {
            $this->products = $products;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству AdminProductsWidget::currency
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
     * Присваивает AdminProductForm свойству AdminProductsWidget::form
     * @param AdminProductForm $form
     */
    public function setForm(AdminProductForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству AdminProductsWidget::header
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
     * Присваивает имя шаблона свойству AdminProductsWidget::template
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

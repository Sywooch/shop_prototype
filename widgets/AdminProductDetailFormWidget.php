<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\{CurrencyInterface,
    ProductsModel};
use app\forms\AdminProductForm;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с формой редактирования данных товара
 */
class AdminProductDetailFormWidget extends AbstractBaseWidget
{
    /**
     * @var ProductsModel
     */
    private $product;
    /**
     * @var array CategoriesModel
     */
    private $categories;
    /**
     * @var array SubcategoryModel
     */
    private $subcategory;
    /**
     * @var array ColorsModel
     */
    private $colors;
    /**
     * @var array SizesModel
     */
    private $sizes;
    /**
     * @var array BrandsModel
     */
    private $brands;
    /**
     * @var AdminProductForm
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
            if (empty($this->product)) {
                throw new ErrorException($this->emptyError('product'));
            }
            if (empty($this->categories)) {
                throw new ErrorException($this->emptyError('categories'));
            }
            if (empty($this->subcategory)) {
                throw new ErrorException($this->emptyError('subcategory'));
            }
            if (empty($this->colors)) {
                throw new ErrorException($this->emptyError('colors'));
            }
            if (empty($this->sizes)) {
                throw new ErrorException($this->emptyError('sizes'));
            }
            if (empty($this->brands)) {
                throw new ErrorException($this->emptyError('brands'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            if (!empty($this->product->images)) {
                $renderArray['images'] = ImgHelper::allThumbn($this->product->images);
            }
            
            $renderArray['modelForm'] = \Yii::configure($this->form, [
                'id'=>$this->product->id,
                'code'=>$this->product->code,
                'name'=>$this->product->name,
                'short_description'=>$this->product->short_description,
                'description'=>$this->product->description,
                'price'=>$this->product->price,
                'id_category'=>$this->product->id_category,
                'id_subcategory'=>$this->product->id_subcategory,
                'id_colors'=>ArrayHelper::getColumn($this->product->colors, 'id'),
                'id_sizes'=>ArrayHelper::getColumn($this->product->sizes, 'id'),
                'id_brand'=>$this->product->id_brand,
                'active'=>$this->product->active,
                'total_products'=>$this->product->total_products,
                'seocode'=>$this->product->seocode,
                'views'=>$this->product->views,
            ]);
            
            $renderArray['formId'] = sprintf('admin-products-detail-send-form-%d', $this->product->id);
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = false;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $renderArray['formAction'] = Url::to(['/admin/product-detail-change']);
            $renderArray['button'] = \Yii::t('base', 'Save');
            $renderArray['buttonCancel'] = \Yii::t('base', 'Cancel');
            
            $renderArray['categories'] = $this->categories;
            $renderArray['subcategory'] = $this->subcategory;
            $renderArray['colors'] = $this->colors;
            $renderArray['sizes'] = $this->sizes;
            $renderArray['brands'] = $this->brands;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel свойству AdminProductDetailFormWidget::product
     * @param ProductsModel $product
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
     * Присваивает array CategoriesModel свойству AdminProductDetailFormWidget::categories
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        try {
            $this->categories = $categories;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductDetailFormWidget::subcategory
     * @param array $subcategory
     */
    public function setSubcategory(array $subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductDetailFormWidget::colors
     * @param array $colors
     */
    public function setColors(array $colors)
    {
        try {
            $this->colors = $colors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductDetailFormWidget::sizes
     * @param array $sizes
     */
    public function setSizes(array $sizes)
    {
        try {
            $this->sizes = $sizes;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array свойству AdminProductDetailFormWidget::brands
     * @param array $brands
     */
    public function setBrands(array $brands)
    {
        try {
            $this->brands = $brands;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает AdminProductForm свойству AdminProductDetailFormWidget::form
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
     * Присваивает имя шаблона свойству AdminProductDetailFormWidget::template
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

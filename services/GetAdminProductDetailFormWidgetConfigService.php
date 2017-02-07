<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\services\AbstractBaseService;
use app\finders\{BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    ProductIdFinder,
    SizesFinder,
    SubcategoryIdCategoryFinder};
use app\forms\AdminProductForm;

/**
 * Возвращает массив конфигурации для виджета AdminProductDetailFormWidget
 */
class GetAdminProductDetailFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var int ID товара
     */
    private $id;
    /**
     * @var array конфигурация для виджета AdminProductDetailFormWidget
     */
    private $adminProductDetailFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @return array
     */
    public function get(): array
    {
        try {
            if (empty($this->id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->adminProductDetailFormWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(ProductIdFinder::class, ['id'=>$this->id]);
                $productsModel = $finder->find();
                if (empty($productsModel)) {
                    throw new ErrorException($this->emptyError('productsModel'));
                }
                $dataArray['product'] = $productsModel;
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesArray = $finder->find();
                if (empty($categoriesArray)) {
                    throw new ErrorException($this->emptyError('categoriesArray'));
                }
                ArrayHelper::multisort($categoriesArray, 'name');
                $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
                $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
                
                $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, ['id_category'=>$productsModel->id_category]);
                $subcategoryArray = $finder->find();
                if (empty($subcategoryArray)) {
                    throw new ErrorException($this->emptyError('subcategoryArray'));
                }
                $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
                $dataArray['subcategory'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $subcategoryArray);
                
                $finder = \Yii::$app->registry->get(ColorsFinder::class);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesFinder::class);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(BrandsFinder::class);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                ArrayHelper::multisort($brandsArray, 'brand');
                $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
                
                $dataArray['form'] = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
                $dataArray['template'] = 'admin-product-detail-form.twig';
                
                $this->adminProductDetailFormWidgetArray = $dataArray;
            }
            
            return $this->adminProductDetailFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству GetAdminProductDetailFormWidgetConfigService::id
     * @param int $id
     */
    public function setId(int $id)
    {
        try {
            $this->id = $id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

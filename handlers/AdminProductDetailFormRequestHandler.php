<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    AdminProductForm};
use app\widgets\AdminProductDetailFormWidget;
use app\finders\{BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    SubcategoryIdCategoryFinder,
    ProductIdFinder,
    SizesFinder};
use app\models\ProductsModel;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей товара
 */
class AdminProductDetailFormRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
           $form = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $finder = \Yii::$app->registry->get(ProductIdFinder::class, [
                        'id'=>$form->id
                    ]);
                    $productsModel = $finder->find();
                    if (empty($productsModel)) {
                        throw new ErrorException($this->emptyError('productsModel'));
                    }
                    
                    $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                    $categoriesArray = $finder->find();
                    if (empty($categoriesArray)) {
                        throw new ErrorException($this->emptyError('categoriesArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, [
                        'id_category'=>$productsModel->id_category
                    ]);
                    $subcategoryArray = $finder->find();
                    if (empty($subcategoryArray)) {
                        throw new ErrorException($this->emptyError('subcategoryArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(ColorsFinder::class);
                    $colorsArray = $finder->find();
                    if (empty($colorsArray)) {
                        throw new ErrorException($this->emptyError('colorsArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(SizesFinder::class);
                    $sizesArray = $finder->find();
                    if (empty($sizesArray)) {
                        throw new ErrorException($this->emptyError('sizesArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(BrandsFinder::class);
                    $brandsArray = $finder->find();
                    if (empty($brandsArray)) {
                        throw new ErrorException($this->emptyError('brandsArray'));
                    }
                    
                    $adminProductForm = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
                    
                    $adminProductDetailFormWidgetConfig = $this->adminProductDetailFormWidgetConfig($productsModel, $categoriesArray, $subcategoryArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
                    
                    return AdminProductDetailFormWidget::widget($adminProductDetailFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminProductDetailFormWidget
     * @param ProductsModel $productsModel
     * @param array $categoriesArray
     * @param array $subcategoryArray
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $brandsArray
     * @param AbstractBaseForm $adminProductForm
     * @return array
     */
    private function adminProductDetailFormWidgetConfig(ProductsModel $productsModel, array $categoriesArray, array $subcategoryArray, array $colorsArray, array $sizesArray, array $brandsArray, AbstractBaseForm $adminProductForm)
    {
        try {
            $dataArray = [];
            
            $dataArray['product'] = $productsModel;
            
            ArrayHelper::multisort($categoriesArray, 'name');
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
            
            $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
            $dataArray['subcategory'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $subcategoryArray);
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
            $dataArray['form'] = $adminProductForm;
            $dataArray['template'] = 'admin-product-detail-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

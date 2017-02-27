<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\{Response,
    UploadedFile};
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\AdminProductForm;
use app\helpers\{ImgHelper,
    TransliterationHelper};
use app\models\ProductsModel;
use app\savers\ModelSaver;
use app\services\{SaveProductsColorsService,
    SaveProductsSizesService,
    SaveRelatedProductsService};
use app\widgets\{AdminAddProductFormWidget,
    AdminProductSaveSuccessWidget};
use app\finders\{BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    SizesFinder};

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminAddProductPostRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $form->images = UploadedFile::getInstances($form, 'images');
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        if (!empty($form->images)) {
                            $imgCatalog = ImgHelper::saveImages($form->images);
                        }
                        
                        $productsModel = new ProductsModel(['scenario'=>ProductsModel::SAVE]);
                        $productsModel->code = $form->code;
                        $productsModel->date = time();
                        $productsModel->name = $form->name;
                        $productsModel->short_description = $form->short_description;
                        $productsModel->description = $form->description;
                        $productsModel->price = $form->price;
                        $productsModel->images = $imgCatalog;
                        $productsModel->id_category = $form->id_category;
                        $productsModel->id_subcategory = $form->id_subcategory;
                        $productsModel->id_brand = $form->id_brand;
                        $productsModel->active = $form->active;
                        $productsModel->total_products = $form->total_products;
                        $productsModel->seocode = empty($form->seocode) ? TransliterationHelper::getTransliterationSeparate($form->name) : $form->seocode;
                        if ($productsModel->validate() === false) {
                            throw new ErrorException($this->modelError($productsModel->errors));
                        }
                        $saver = new ModelSaver([
                            'model'=>$productsModel
                        ]);
                        $saver->save();
                        
                        $service = new SaveProductsColorsService([
                            'idColors'=>$form->id_colors,
                            'idProduct'=>$productsModel->id
                        ]);
                        $service->get();
                        
                        $service = new SaveProductsSizesService([
                            'idSizes'=>$form->id_sizes,
                            'idProduct'=>$productsModel->id
                        ]);
                        $service->get();
                        
                        if (!empty($form->related)) {
                            $service = new SaveRelatedProductsService([
                                'idRelatedProducts'=>explode(',', $form->related),
                                'idProduct'=>$productsModel->id
                            ]);
                            $service->get();
                        }
                        
                        $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                        $categoriesArray = $finder->find();
                        if (empty($categoriesArray)) {
                            throw new ErrorException($this->emptyError('categoriesArray'));
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
                        
                        $adminProductForm = new AdminProductForm(['scenario'=>AdminProductForm::CREATE]);
                        
                        $dataArray = [];
                        
                        $dataArray['successText'] = AdminProductSaveSuccessWidget::widget(['template'=>'admin-product-save-success.twig']);
                        $adminAddProductFormWidgetConfig = $this->adminAddProductFormWidgetConfig($categoriesArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
                        $dataArray['form'] = AdminAddProductFormWidget::widget($adminAddProductFormWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $dataArray;
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        ImgHelper::removeImages($imgCatalog ?? '');
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

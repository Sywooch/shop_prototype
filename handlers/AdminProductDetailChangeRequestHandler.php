<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\{Response,
    UploadedFile};
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    AdminProductForm};
use app\helpers\{HashHelper,
    ImgHelper};
use app\finders\ProductIdFinder;
use app\models\{CurrencyInterface,
    ProductsModel};
use app\savers\ModelSaver;
use app\services\{GetCurrentCurrencyModelService,
    SaveProductsColorsService,
    SaveProductsSizesService,
    SaveRelatedProductsService};
use app\widgets\AdminProductDataWidget;

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminProductDetailChangeRequestHandler extends AbstractBaseHandler
{
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminProductForm(['scenario'=>AdminProductForm::EDIT]);
            
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
                        $finder = \Yii::$app->registry->get(ProductIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $productsModel = $finder->find();
                        if (empty($productsModel)) {
                            throw new ErrorException($this->emptyError('productsModel'));
                        }
                        
                        if (!empty($form->images)) {
                            if (!empty($productsModel->images)) {
                                ImgHelper::removeImages($productsModel->images);
                            }
                            $imgCatalog = ImgHelper::saveImages($form->images);
                        }
                        
                        $productsModel->scenario = ProductsModel::EDIT;
                        $productsModel->code = $form->code;
                        $productsModel->name = $form->name;
                        $productsModel->short_description = $form->short_description;
                        $productsModel->description = $form->description;
                        $productsModel->price = $form->price;
                        $productsModel->images = $imgCatalog ?? $productsModel->images;
                        $productsModel->id_category = $form->id_category;
                        $productsModel->id_subcategory = $form->id_subcategory;
                        $productsModel->id_brand = $form->id_brand;
                        $productsModel->active = $form->active;
                        $productsModel->total_products = $form->total_products;
                        $productsModel->seocode = $form->seocode;
                        $productsModel->views = $form->views;
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
                        
                        $service = new SaveRelatedProductsService([
                            'idRelatedProducts'=>!empty($form->related) ? explode(',', $form->related) : [],
                            'idProduct'=>$productsModel->id
                        ]);
                        $service->get();
                        
                        $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                            'key'=>HashHelper::createCurrencyKey()
                        ]);
                        $currentCurrencyModel = $service->get();
                        if (empty($currentCurrencyModel)) {
                            throw new ErrorException($this->emptyError('currentCurrencyModel'));
                        }
                        
                        $adminProductForm = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
                        
                        $adminProductDataWidgetConfig = $this->adminProductDataWidgetConfig($productsModel, $currentCurrencyModel, $adminProductForm);
                        $response = AdminProductDataWidget::widget($adminProductDataWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
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
    
    /**
     * Возвращает массив настроек для виджета AdminProductDataWidget
     * @param Model $productsModel
     * @param CurrencyInterface $currentCurrencyModel
     * @param AbstractBaseForm $form
     */
    private function adminProductDataWidgetConfig(Model $productsModel, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $adminProductForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['productsModel'] = $productsModel;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $adminProductForm;
            $dataArray['template'] = 'admin-product-data.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

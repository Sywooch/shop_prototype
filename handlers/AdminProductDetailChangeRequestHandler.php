<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\AdminProductForm;
use app\helpers\ImgHelper;
use app\finders\ProductIdFinder;
use app\models\{ProductsColorsModel,
    ProductsModel,
    ProductsSizesModel};
use app\savers\{ModelSaver,
    ProductsColorsArraySaver,
    ProductsSizesArraySaver};

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
                        
                        /*if (!empty($form->images)) {
                            $imgCatalog = ImgHelper::saveImages($form, 'images');
                            if (empty($imgCatalog)) {
                                throw new ErrorException($this->emptyError('imgCatalog'));
                            }
                        }*/
                        $imgCatalog = ImgHelper::saveImages($form, 'images');
                        if (empty($imgCatalog)) {
                            throw new ErrorException($this->emptyError('imgCatalog'));
                        }
                        
                        $productsModel->scenario = ProductsModel::EDIT;
                        $productsModel->code = $form->code;
                        $productsModel->name = $form->name;
                        $productsModel->short_description = $form->short_description;
                        $productsModel->description = $form->description;
                        $productsModel->price = $form->price;
                        $productsModel->images = $imgCatalog ?? $form->images ?? '';
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
                        
                        $this->saveProductsColors($form);
                        $this->saveProductsSizes($form);
                        
                        $transaction->commit();
                        
                        return 'CHNAGED';
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        if (!empty($imgCatalog)) {
                            $this->cleanFiles($imgCatalog);
                        }
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет ProductsColorsModel
     * @param AdminProductForm $form
     * @return bool
     */
    private function saveProductsColors(AdminProductForm $form)
    {
        try {
            $productsColorsModel = new ProductsColorsModel(['scenario'=>ProductsColorsModel::SAVE]);
            $productsColorsModelArray = [];
            
            foreach ($form->id_colors as $idColor) {
                $rawProductsColorsModel = clone $productsColorsModel;
                $rawProductsColorsModel->id_product = $form->id;
                $rawProductsColorsModel->id_color = $idColor;
                if ($rawProductsColorsModel->validate() === false) {
                    throw new ErrorException($this->modelError($rawProductsColorsModel->errors));
                }
                $productsColorsModelArray[] = $rawProductsColorsModel;
            }
            
            $saver = new ProductsColorsArraySaver([
                'models'=>$productsColorsModelArray
            ]);
            $saver->save();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Сохраняет ProductsSizesModel
     * @param AdminProductForm $form
     * @return bool
     */
    private function saveProductsSizes(AdminProductForm $form)
    {
        try {
            $productsSizesModel = new ProductsSizesModel(['scenario'=>ProductsSizesModel::SAVE]);
            $productsSizesModelArray = [];
            
            foreach ($form->id_sizes as $idSize) {
                $rawProductsSizesModel = clone $productsSizesModel;
                $rawProductsSizesModel->id_product = $form->id;
                $rawProductsSizesModel->id_size = $idSize;
                if ($rawProductsSizesModel->validate() === false) {
                    throw new ErrorException($this->modelError($rawProductsSizesModel->errors));
                }
                $productsSizesModelArray[] = $rawProductsSizesModel;
            }
            
            $saver = new ProductsSizesArraySaver([
                'models'=>$productsSizesModelArray
            ]);
            $saver->save();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Удаляет сохраненные файлы при ошибке выполнения
     * @param string $imgCatalog
     * @return bool
     */
    private function cleanFiles(string $imgCatalog)
    {
        try {
            if (file_exists($imgCatalog) && is_dir($imgCatalog)) {
                $fileArray = glob(sprintf('%s/*.{jpg,gif,png}', \Yii::getAlias(sprintf('@imagesroot/%s', $imgCatalog))));
                if (!empty($fileArray)) {
                    foreach ($fileArray as $file) {
                        unlink($file);
                    }
                }
                rmdir($imgCatalog);
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\AdminProductForm;
use app\helpers\ImgHelper;
use app\finders\ProductIdFinder;
use app\models\ProductsColorsModel;

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
                        
                        $imgCatalog = ImgHelper::saveImages($form, 'images');
                        if (empty($imgCatalog)) {
                            throw new ErrorException($this->emptyError('imgCatalog'));
                        }
                        
                        $productsModel->code = $form->code;
                        $productsModel->name = $form->name;
                        $productsModel->short_description = $form->short_description;
                        $productsModel->description = $form->description;
                        $productsModel->price = $form->price;
                        $productsModel->images = $imgCatalog;
                        $productsModel->id_category = $form->id_category;
                        $productsModel->id_subcategory = $form->id_subcategory;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        $productsModel->xxx = $form->xxx;
                        
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
                        
                        $transaction->commit();
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

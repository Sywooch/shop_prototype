<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\web\UploadedFile;
use yii\db\Transaction;
use app\controllers\AbstractBaseController;
use app\models\{BrandsModel,
    ColorsModel,
    ProductsModel,
    SizesModel};
use app\queries\QueryTrait;
use app\helpers\InstancesHelper;

/**
 * Управляет добавлением, удвлением, изменением товаров
 */
class ProductsManagerController extends AbstractBaseController
{
    use QueryTrait;
    
    /**
     * Управляет процессом добавления 1 товара
     */
    public function actionAddOne()
    {
        try {
            $rawProductsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
            $rawColorsModel = new ColorsModel(['scenario'=>ColorsModel::GET_FROM_ADD_PRODUCT]);
            $rawSizesModel = new SizesModel(['scenario'=>SizesModel::GET_FROM_ADD_PRODUCT]);
            $rawBrandsModel = new BrandsModel(['scenario'=>BrandsModel::GET_FROM_ADD_PRODUCT]);
            
            if (\Yii::$app->request->isPost && $rawProductsModel->load(\Yii::$app->request->post()) && $rawColorsModel->load(\Yii::$app->request->post()) && $rawSizesModel->load(\Yii::$app->request->post()) && $rawBrandsModel->load(\Yii::$app->request->post())) {
                if ($rawProductsModel->validate() && $rawColorsModel->validate() && $rawSizesModel->validate() && $rawBrandsModel->validate()) {
                    
                    $transaction = \Yii::$db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!empty($rawProductsModel->images)) {
                            $rawProductsModel->images = UploadedFile::getInstances($rawProductsModel, 'images');
                            $folderName = $rawProductsModel->upload();
                            $rawProductsModel->images = $folderName;
                        }
                        
                        $transaction->commit();
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                    
                }
            }
            
            $renderArray = [];
            
            $renderArray['productsModel'] = $rawProductsModel;
            
            $renderArray['colorsModel'] = $rawColorsModel;
            $renderArray['colorsList'] = $this->colorsListQueryAll()->all();
            if (!$renderArray['colorsList'][0] instanceof ColorsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ColorsModel']));
            }
            
            $renderArray['sizesModel'] = $rawSizesModel;
            $renderArray['sizesList'] = $this->sizesListQueryAll()->all();
            if (!$renderArray['sizesList'][0] instanceof SizesModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'SizesModel']));
            }
            
            $renderArray['brandsModel'] = $rawBrandsModel;
            $renderArray['brandsList'] = $this->brandsListQueryAll()->all();
            if (!$renderArray['brandsList'][0] instanceof BrandsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'BrandsModel']));
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-manager/add-one'], 'label'=>\Yii::t('base', 'Add product')];
            
            return $this->render('add-one.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}

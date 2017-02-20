<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\AdminProductForm;
use app\finders\ProductIdFinder;
use app\models\ProductsModel;
use app\removers\ProductsModelRemover;
use app\helpers\ImgHelper;

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminProductDetailDeleteRequestHandler extends AbstractBaseHandler
{
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminProductForm(['scenario'=>AdminProductForm::DELETE]);
            
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
                        
                        $productsModel->scenario = ProductsModel::DELETE;
                        if ($productsModel->validate() === false) {
                            throw new ErrorException($this->modelError($productsModel->errors));
                        }
                        
                        $remover = new ProductsModelRemover([
                            'model'=>$productsModel
                        ]);
                        $remover->remove();
                        
                        ImgHelper::removeImages($productsModel->images);
                        
                        $transaction->commit();
                        
                        return \Yii::t('base', 'Deleted');
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

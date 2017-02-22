<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\BrandsForm;
use app\models\BrandsModel;
use app\removers\BrandsModelRemover;
use app\widgets\AdminBrandsWidget;
use app\finders\{BrandIdFinder,
    BrandsFinder};

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminBrandDeleteRequestHandler extends AbstractBaseHandler
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
            $form = new BrandsForm(['scenario'=>BrandsForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(BrandIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $brandsModel = $finder->find();
                        if (empty($brandsModel)) {
                            throw new ErrorException($this->emptyError('brandsModel'));
                        }
                        
                        $brandsModel->scenario = BrandsModel::DELETE;
                        if ($brandsModel->validate() === false) {
                            throw new ErrorException($this->modelError($brandsModel->errors));
                        }
                        
                        $remover = new BrandsModelRemover([
                            'model'=>$brandsModel
                        ]);
                        $remover->remove();
                        
                        $finder = \Yii::$app->registry->get(BrandsFinder::class);
                        $brandsModelArray = $finder->find();
                        
                        $brandsForm = new BrandsForm(['scenario'=>BrandsForm::DELETE]);
                        
                        $dataArray = [];
                        
                        $adminBrandsWidgetConfig = $this->adminBrandsWidgetConfig($brandsModelArray, $brandsForm);
                        $response = AdminBrandsWidget::widget($adminBrandsWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
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

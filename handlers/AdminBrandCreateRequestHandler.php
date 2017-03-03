<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\BrandsForm;
use app\savers\ModelSaver;
use app\finders\BrandsFinder;
use app\widgets\AdminBrandsWidget;
use app\models\BrandsModel;

/**
 * Обрабатывает запрос на создание категории
 */
class AdminBrandCreateRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Создает категорию
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new BrandsForm(['scenario'=>BrandsForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawBrandsModel = new BrandsModel(['scenario'=>BrandsModel::CREATE]);
                        $rawBrandsModel->brand = $form->brand;
                        if ($rawBrandsModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawBrandsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawBrandsModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(BrandsFinder::class);
                        $brandsModelArray = $finder->find();
                        
                        $brandsForm = new BrandsForm();
                        
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

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\SizesForm;
use app\savers\ModelSaver;
use app\finders\SizesFinder;
use app\widgets\AdminSizesWidget;
use app\models\SizesModel;

/**
 * Обрабатывает запрос на создание категории
 */
class AdminSizeCreateRequestHandler extends AbstractBaseHandler
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
            $form = new SizesForm(['scenario'=>SizesForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawSizesModel = new SizesModel(['scenario'=>SizesModel::CREATE]);
                        $rawSizesModel->size = $form->size;
                        if ($rawSizesModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawSizesModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawSizesModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(SizesFinder::class);
                        $sizesModelArray = $finder->find();
                        
                        $sizesForm = new SizesForm(['scenario'=>SizesForm::DELETE]);
                        
                        $dataArray = [];
                        
                        $adminSizesWidgetConfig = $this->adminSizesWidgetConfig($sizesModelArray, $sizesForm);
                        $response = AdminSizesWidget::widget($adminSizesWidgetConfig);
                        
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

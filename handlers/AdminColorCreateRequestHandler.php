<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\ColorsForm;
use app\savers\ModelSaver;
use app\finders\ColorsFinder;
use app\widgets\AdminColorsWidget;
use app\models\ColorsModel;

/**
 * Обрабатывает запрос на создание категории
 */
class AdminColorCreateRequestHandler extends AbstractBaseHandler
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
            $form = new ColorsForm(['scenario'=>ColorsForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawColorsModel = new ColorsModel(['scenario'=>ColorsModel::CREATE]);
                        $rawColorsModel->color = $form->color;
                        if ($rawColorsModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawColorsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawColorsModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(ColorsFinder::class);
                        $colorsModelArray = $finder->find();
                        
                        $colorsForm = new ColorsForm();
                        
                        $dataArray = [];
                        
                        $adminColorsWidgetConfig = $this->adminColorsWidgetConfig($colorsModelArray, $colorsForm);
                        $response = AdminColorsWidget::widget($adminColorsWidgetConfig);
                        
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

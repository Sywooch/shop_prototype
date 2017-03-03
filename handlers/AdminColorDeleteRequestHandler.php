<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\ColorsForm;
use app\models\ColorsModel;
use app\removers\ColorsModelRemover;
use app\widgets\AdminColorsWidget;
use app\finders\{ColorIdFinder,
    ColorsFinder};

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminColorDeleteRequestHandler extends AbstractBaseHandler
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
            $form = new ColorsForm(['scenario'=>ColorsForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(ColorIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $colorsModel = $finder->find();
                        if (empty($colorsModel)) {
                            throw new ErrorException($this->emptyError('colorsModel'));
                        }
                        
                        $colorsModel->scenario = ColorsModel::DELETE;
                        if ($colorsModel->validate() === false) {
                            throw new ErrorException($this->modelError($colorsModel->errors));
                        }
                        
                        $remover = new ColorsModelRemover([
                            'model'=>$colorsModel
                        ]);
                        $remover->remove();
                        
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

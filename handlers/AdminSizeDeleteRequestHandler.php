<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\SizesForm;
use app\models\SizesModel;
use app\removers\SizesModelRemover;
use app\widgets\AdminSizesWidget;
use app\finders\{SizeIdFinder,
    SizesFinder};

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminSizeDeleteRequestHandler extends AbstractBaseHandler
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
            $form = new SizesForm(['scenario'=>SizesForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(SizeIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $sizesModel = $finder->find();
                        if (empty($sizesModel)) {
                            throw new ErrorException($this->emptyError('sizesModel'));
                        }
                        
                        $sizesModel->scenario = SizesModel::DELETE;
                        if ($sizesModel->validate() === false) {
                            throw new ErrorException($this->modelError($sizesModel->errors));
                        }
                        
                        $remover = new SizesModelRemover([
                            'model'=>$sizesModel
                        ]);
                        $remover->remove();
                        
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

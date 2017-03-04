<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\DeliveriesForm;
use app\savers\ModelSaver;
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\widgets\AdminDeliveriesWidget;
use app\models\DeliveriesModel;
use app\finders\AdminDeliveriesFinder;

/**
 * Обрабатывает запрос на создание категории
 */
class AdminDeliveryCreateRequestHandler extends AbstractBaseHandler
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
            $form = new DeliveriesForm(['scenario'=>DeliveriesForm::CREATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $rawDeliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::CREATE]);
                        $rawDeliveriesModel->attributes = $form->toArray();
                        if ($rawDeliveriesModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawDeliveriesModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$rawDeliveriesModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(AdminDeliveriesFinder::class);
                        $deliveriesModelArray = $finder->find();
                        
                        $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                            'key'=>HashHelper::createCurrencyKey()
                        ]);
                        $currentCurrencyModel = $service->get();
                        if (empty($currentCurrencyModel)) {
                            throw new ErrorException($this->emptyError('currentCurrencyModel'));
                        }
                        
                        $deliveriesForm = new DeliveriesForm();
                        
                        $adminDeliveriesWidgetConfig = $this->adminDeliveriesWidgetConfig($deliveriesModelArray, $currentCurrencyModel, $deliveriesForm);
                        $response = AdminDeliveriesWidget::widget($adminDeliveriesWidgetConfig);
                        
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

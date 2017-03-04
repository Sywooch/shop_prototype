<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\DeliveriesForm;
use app\models\DeliveriesModel;
use app\removers\DeliveriesModelRemover;
use app\widgets\AdminDeliveriesWidget;
use app\finders\{AdminDeliveriesFinder,
    DeliveryIdFinder};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на удаление данных о доставке
 */
class AdminDeliveryDeleteRequestHandler extends AbstractBaseHandler
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
            $form = new DeliveriesForm(['scenario'=>DeliveriesForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(DeliveryIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $deliveriesModel = $finder->find();
                        if (empty($deliveriesModel)) {
                            throw new ErrorException($this->emptyError('deliveriesModel'));
                        }
                        
                        $deliveriesModel->scenario = DeliveriesModel::DELETE;
                        if ($deliveriesModel->validate() === false) {
                            throw new ErrorException($this->modelError($deliveriesModel->errors));
                        }
                        
                        $remover = new DeliveriesModelRemover([
                            'model'=>$deliveriesModel
                        ]);
                        $remover->remove();
                        
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

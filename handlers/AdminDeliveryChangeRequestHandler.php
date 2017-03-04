<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    DeliveriesForm};
use app\savers\ModelSaver;
use app\models\DeliveriesModel;
use app\finders\DeliveryIdFinder;
use app\widgets\AdminDeliveryDataWidget;
use app\services\GetCurrentCurrencyModelService;
use app\models\CurrencyInterface;
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на обновление заказа
 */
class AdminDeliveryChangeRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на отмену заказа
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new DeliveriesForm(['scenario'=>DeliveriesForm::EDIT]);
            
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
                        
                        $deliveriesModel->scenario = DeliveriesModel::EDIT;
                        $deliveriesModel->attributes = $form->toArray();
                        if ($deliveriesModel->validate() === false) {
                            throw new ErrorException($this->modelError($deliveriesModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$deliveriesModel
                        ]);
                        $saver->save();
                        
                        $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                            'key'=>HashHelper::createCurrencyKey()
                        ]);
                        $currentCurrencyModel = $service->get();
                        if (empty($currentCurrencyModel)) {
                            throw new ErrorException($this->emptyError('currentCurrencyModel'));
                        }
                        
                        $deliveryForm = new DeliveriesForm();
                        
                        $adminDeliveryDataWidgetConfig = $this->adminDeliveryDataWidgetConfig($deliveriesModel, $currentCurrencyModel, $deliveryForm);
                        $response = AdminDeliveryDataWidget::widget($adminDeliveryDataWidgetConfig);
                        
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
    
    /**
     * Возвращает массив конфигурации для виджета AdminDeliveryDataWidget
     * @params Model $deliveriesModel
     * @param CurrencyInterface $currentCurrencyModel
     * @param AbstractBaseForm $deliveryForm
     * @return array
     */
    private function adminDeliveryDataWidgetConfig(Model $deliveriesModel, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $deliveryForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['delivery'] = $deliveriesModel;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $deliveryForm;
            $dataArray['template'] = 'admin-delivery-data.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

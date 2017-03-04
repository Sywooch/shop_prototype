<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    DeliveriesForm};
use app\widgets\AdminDeliveryFormWidget;
use app\finders\DeliveryIdFinder;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей типа доставки
 */
class AdminDeliveryFormRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
           $form = new DeliveriesForm(['scenario'=>DeliveriesForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $finder = \Yii::$app->registry->get(DeliveryIdFinder::class, [
                        'id'=>$form->id
                    ]);
                    $deliveriesModel = $finder->find();
                    if (empty($deliveriesModel)) {
                        throw new ErrorException($this->emptyError('deliveriesModel'));
                    }
                    
                    $deliveryForm = new DeliveriesForm();
                    
                    $adminDeliveryFormWidgetConfig = $this->adminDeliveryFormWidgetConfig($deliveriesModel, $deliveryForm);
                    
                    return AdminDeliveryFormWidget::widget($adminDeliveryFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminDeliveryFormWidget
     * @param Model $deliveriesModel
     * @param AbstractBaseForm $deliveryForm
     * @return array
     */
    private function adminDeliveryFormWidgetConfig(Model $deliveriesModel, AbstractBaseForm $deliveryForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['delivery'] = $deliveriesModel;
            $dataArray['form'] = $deliveryForm;
            $dataArray['template'] = 'admin-delivery-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

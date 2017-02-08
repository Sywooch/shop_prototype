<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\handlers\AbstractBaseHandler;
use app\services\{GetAdminOrderDetailFormWidgetConfigService,
    GetCurrentCurrencyModelService};
use app\forms\AdminChangeOrderForm;
use app\widgets\AdminOrderDetailFormWidget;
use app\finders\{ColorsProductFinder,
    DeliveriesFinder,
    OrderStatusesFinder,
    PaymentsFinder,
    PurchaseIdFinder,
    SizesProductFinder};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей заказа
 */
class AdminOrderDetailFormRequestHandler extends AbstractBaseHandler
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
           $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    /*$service = \Yii::$app->registry->get(GetAdminOrderDetailFormWidgetConfigService::class);
                    $adminOrderDetailFormWidgetConfig = $service->handle(['id'=>$form->id]);*/
                    
                    $adminOrderDetailFormWidgetConfig = $this->adminOrderDetailFormWidgetConfig($form->id);
                    
                    return AdminOrderDetailFormWidget::widget($adminOrderDetailFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminOrderDetailFormWidget
     * @params int $id товара, для которого запрашивается форма редактирования
     * @return array
     */
    private function adminOrderDetailFormWidgetConfig(int $id): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, [
                'id'=>$id
            ]);
            $purchasesModel = $finder->find();
            if (empty($purchasesModel)) {
                throw new ErrorException($this->emptyError('purchasesModel'));
            }
            $dataArray['purchase'] = $purchasesModel;
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $dataArray['currency'] = $service->get();
            
            $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
            $statusesArray = $finder->find();
            if (empty($statusesArray)) {
                throw new ErrorException($this->emptyError('statusesArray'));
            }
            $dataArray['statuses'] = $statusesArray;
            
            $dataArray['form'] = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
            
            $finder = \Yii::$app->registry->get(ColorsProductFinder::class, [
                'id_product'=>$purchasesModel->id_product
            ]);
            $colorsArray = $finder->find();
            if (empty($colorsArray)) {
                throw new ErrorException($this->emptyError('colorsArray'));
            }
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            $finder = \Yii::$app->registry->get(SizesProductFinder::class, [
                'id_product'=>$purchasesModel->id_product
            ]);
            $sizesArray = $finder->find();
            if (empty($sizesArray)) {
                throw new ErrorException($this->emptyError('sizesArray'));
            }
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            $finder = \Yii::$app->registry->get(DeliveriesFinder::class);
            $deliveriesArray = $finder->find();
            if (empty($deliveriesArray)) {
                throw new ErrorException($this->emptyError('deliveriesArray'));
            }
            ArrayHelper::multisort($deliveriesArray, 'description');
            $dataArray['deliveries'] = ArrayHelper::map($deliveriesArray, 'id', 'description');
            
            $finder = \Yii::$app->registry->get(PaymentsFinder::class);
            $paymentsArray = $finder->find();
            if (empty($paymentsArray)) {
                throw new ErrorException($this->emptyError('paymentsArray'));
            }
            ArrayHelper::multisort($paymentsArray, 'description');
            $dataArray['payments'] = ArrayHelper::map($paymentsArray, 'id', 'description');
            
            $dataArray['template'] = 'admin-order-detail-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

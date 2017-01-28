<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\{ColorsProductFinder,
    DeliveriesFinder,
    OrderStatusesFinder,
    PaymentsFinder,
    PurchaseIdFinder,
    SizesProductFinder};
use app\forms\AdminChangeOrderForm;

/**
 * Возвращает массив конфигурации для виджета AdminOrderDetailFormWidget
 */
class GetAdminOrderDetailFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminOrderDetailFormWidget
     */
    private $adminOrderDetailFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $id = $request['id'] ?? null;
            
            if (empty($id)) {
                throw new ErrorException($this->emptyError('id'));
            }
            
            if (empty($this->adminOrderDetailFormWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, ['id'=>$id]);
                $purchasesModel = $finder->find();
                
                if (empty($purchasesModel)) {
                    throw new ErrorException($this->emptyError('purchasesModel'));
                }
                
                $dataArray['purchase'] = $purchasesModel;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
                $dataArray['statuses'] = $finder->find();
                
                $dataArray['form'] = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::SAVE]);
                
                $finder = \Yii::$app->registry->get(ColorsProductFinder::class, ['id_product'=>$purchasesModel->id_product]);
                $colors = $finder->find();
                $dataArray['colors'] = ArrayHelper::map($colors, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesProductFinder::class, ['id_product'=>$purchasesModel->id_product]);
                $sizes = $finder->find();
                $dataArray['sizes'] = ArrayHelper::map($sizes, 'id', 'size');
                
                $finder = \Yii::$app->registry->get(DeliveriesFinder::class);
                $deliveries = $finder->find();
                $dataArray['deliveries'] = ArrayHelper::map($deliveries, 'id', 'description');
                
                $finder = \Yii::$app->registry->get(PaymentsFinder::class);
                $payments = $finder->find();
                $dataArray['payments'] = ArrayHelper::map($payments, 'id', 'description');
                
                $dataArray['template'] = 'admin-order-detail-form.twig';
                
                $this->adminOrderDetailFormWidgetArray = $dataArray;
            }
            
            return $this->adminOrderDetailFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

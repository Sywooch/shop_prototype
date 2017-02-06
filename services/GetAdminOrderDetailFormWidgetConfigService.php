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
                $statusesArray = $finder->find();
                if (empty($statusesArray)) {
                    throw new ErrorException($this->emptyError('statusesArray'));
                }
                $dataArray['statuses'] = $statusesArray;
                
                $dataArray['form'] = new AdminChangeOrderForm([
                    'scenario'=>AdminChangeOrderForm::SAVE
                ]);
                
                $finder = \Yii::$app->registry->get(ColorsProductFinder::class, ['id_product'=>$purchasesModel->id_product]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = \Yii::$app->registry->get(SizesProductFinder::class, ['id_product'=>$purchasesModel->id_product]);
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
                
                $this->adminOrderDetailFormWidgetArray = $dataArray;
            }
            
            return $this->adminOrderDetailFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

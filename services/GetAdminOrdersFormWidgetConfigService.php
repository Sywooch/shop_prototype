<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService,
    GetPurchasesCollectionService};
use app\forms\OrderStatusForm;
use app\finders\OrderStatusesFinder;

/**
 * Возвращает массив конфигурации для виджета AdminOrdersFormWidget
 */
class GetAdminOrdersFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminOrdersFormWidget
     */
    private $adminOrdersFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->adminOrdersFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders');
                
                $service = \Yii::$app->registry->get(GetPurchasesCollectionService::class);
                $purchasesCollection = $service->handle($request);
                
                if ($purchasesCollection->isEmpty() === true) {
                    if ($purchasesCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $dataArray['purchases'] = $purchasesCollection->asArray();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
                $dataArray['statuses'] = $finder->find();
                
                $dataArray['form'] = new OrderStatusForm(['scenario'=>OrderStatusForm::SAVE]);
                
                $dataArray['view'] = 'admin-orders-form.twig';
                
                $this->adminOrdersFormWidgetArray = $dataArray;
            }
            
            return $this->adminOrdersFormWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

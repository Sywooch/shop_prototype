<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    AdminOrdersCollectionService,
    GetCurrentCurrencyModelService};
use app\forms\AdminChangeOrderForm;
use app\finders\OrderStatusesFinder;

/**
 * Возвращает массив конфигурации для виджета AdminOrdersWidget
 */
class GetAdminOrdersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminOrdersWidget
     */
    private $adminOrdersWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->adminOrdersWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders');
                
                $service = \Yii::$app->registry->get(AdminOrdersCollectionService::class);
                $purchasesCollection = $service->handle($request);
                
                if ($purchasesCollection->isEmpty() === true) {
                    if ($purchasesCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $dataArray['purchases'] = $purchasesCollection->asArray();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['form'] = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
                
                $dataArray['template'] = 'admin-orders.twig';
                
                $this->adminOrdersWidgetArray = $dataArray;
            }
            
            return $this->adminOrdersWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

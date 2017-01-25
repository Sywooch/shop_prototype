<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesFinder;
use app\forms\OrderStatusForm;

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
    public function handle($request=null): array
    {
        try {
            if (empty($this->adminOrdersFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders');
                
                $finder = \Yii::$app->registry->get(PurchasesFinder::class);
                $dataArray['purchases'] = $finder->find()->asArray();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['form'] = new OrderStatusForm(['scenario'=>OrderStatusForm::SAVE]);
                
                $dataArray['view'] = 'admin-orders-form.twig';
                
                $this->adminOrdersFormWidgetArray = $dataArray;
            }
            
            return $this->adminOrdersFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    AdminOrdersCollectionService,
    GetCurrentCurrencyModelService};
use app\forms\AdminChangeOrderForm;
use app\finders\PurchaseIdFinder;

/**
 * Возвращает массив конфигурации для виджета AdminOrderDataWidget
 */
class GetAdminOrderDataWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AdminOrderDataWidget
     */
    private $adminOrderDataWidgetArray = [];
    
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
            
            if (empty($this->adminOrderDataWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchaseIdFinder::class, ['id'=>$id]);
                $purchasesModel = $finder->find();
                
                if (empty($purchasesModel)) {
                    throw new ErrorException($this->emptyError('purchasesModel'));
                }
                
                $dataArray['purchase'] = $purchasesModel;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['form'] = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
                
                $dataArray['template'] = 'admin-order-data.twig';
                
                $this->adminOrderDataWidgetArray = $dataArray;
            }
            
            return $this->adminOrderDataWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesIdUserFinder;

/**
 * Возвращает массив конфигурации для виджета AccountOrdersWidget
 */
class GetAccountOrdersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountOrdersWidget
     */
    private $accountPurchasesWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            if (empty($this->accountPurchasesWidgetArray)) {
                $dataArray = [];
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, ['id_user'=>$user->id]);
                $dataArray['purchases'] = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'account-purchases.twig';
                
                $this->accountPurchasesWidgetArray = $dataArray;
            }
            
            return $this->accountPurchasesWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

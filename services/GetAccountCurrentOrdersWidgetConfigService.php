<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesIdUserFinder;

/**
 * Возвращает массив конфигурации для виджета AccountCurrentOrdersWidget
 */
class GetAccountCurrentOrdersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountCurrentOrdersWidget
     */
    private $accountCurrentOrdersWidgetArray = [];
    
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
            
            if (empty($this->accountCurrentOrdersWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Current orders');
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, ['id_user'=>$user->id]);
                $dataArray['purchases'] = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['template'] = 'account-current-orders.twig';
                
                $this->accountCurrentOrdersWidgetArray = $dataArray;
            }
            
            return $this->accountCurrentOrdersWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

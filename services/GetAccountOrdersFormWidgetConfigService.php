<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesIdUserFinder;
use app\forms\PurchaseForm;

/**
 * Возвращает массив конфигурации для виджета AccountOrdersFormWidget
 */
class GetAccountOrdersFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountOrdersFormWidget
     */
    private $accountOrdersFormWidgetArray = [];
    
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
            
            if (empty($this->accountOrdersFormWidgetArray)) {
                $dataArray = [];
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, ['id_user'=>$user->id]);
                $dataArray['purchases'] = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['form'] = new PurchaseForm(['scenario'=>PurchaseForm::CANCEL]);
                
                $dataArray['header'] = \Yii::t('base', 'Orders');
                
                $dataArray['template'] = 'account-orders-form.twig';
                
                $this->accountOrdersFormWidgetArray = $dataArray;
            }
            
            return $this->accountOrdersFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

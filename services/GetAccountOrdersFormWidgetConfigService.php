<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    AccountOrdersCollectionService,
    GetCurrentCurrencyModelService};
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
    public function handle($request): array
    {
        try {
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            if (empty($this->accountOrdersFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Orders');
                
                $service = \Yii::$app->registry->get(AccountOrdersCollectionService::class);
                $purchasesCollection = $service->handle($request);
                
                if ($purchasesCollection->isEmpty() === true) {
                    if ($purchasesCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $dataArray['purchases'] = $purchasesCollection->asArray();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['form'] = new PurchaseForm(['scenario'=>PurchaseForm::CANCEL]);
                
                $dataArray['template'] = 'account-orders-form.twig';
                
                $this->accountOrdersFormWidgetArray = $dataArray;
            }
            
            return $this->accountOrdersFormWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    AccountOrdersCollectionService,
    GetCurrentCurrencyModelService};
use app\forms\PurchaseForm;

/**
 * Возвращает массив конфигурации для виджета AccountOrdersWidget
 */
class GetAccountOrdersWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountOrdersWidget
     */
    private $accountOrdersWidgetArray = [];
    
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
            
            if (empty($this->accountOrdersWidgetArray)) {
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
                
                $dataArray['template'] = 'account-orders.twig';
                
                $this->accountOrdersWidgetArray = $dataArray;
            }
            
            return $this->accountOrdersWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

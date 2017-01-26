<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\{CustomerInfoSessionFinder,
    PurchasesSessionFinder};
use app\helpers\HashHelper;

/**
 * Возвращает конфигурацию для виджета EmailReceivedOrderWidget
 */
class GetEmailReceivedOrderWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета EmailReceivedOrderWidget
     */
    private $emailReceivedOrderWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета EmailReceivedOrderWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->emailReceivedOrderWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, ['key'=>HashHelper::createCartKey()]);
                $dataArray['purchases'] = $finder->find();
                
                $finder = \Yii::$app->registry->get(CustomerInfoSessionFinder::class, ['key'=>HashHelper::createCartCustomerKey()]);
                $dataArray['form'] = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['header'] = \Yii::t('base', 'Hello! This is information about your order!');
                
                $dataArray['template'] = 'email-received-order-mail.twig';
                
                $this->emailReceivedOrderWidgetArray = $dataArray;
            }
            
            return $this->emailReceivedOrderWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

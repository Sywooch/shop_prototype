<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\PurchasesIdUserFinder;

/**
 * Возвращает массив конфигурации для виджета AccountGeneralWidget
 */
class GetAccountGeneralWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountGeneralWidget
     */
    private $accountGeneralWidgetArray = [];
    
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
            
            if (empty($this->accountGeneralWidgetArray)) {
                $dataArray = [];
                
                $dataArray['user'] = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, ['id_user'=>\Yii::$app->user->id]);
                $dataArray['purchases'] = $finder->find();
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['view'] = 'account-general.twig';
                
                $this->accountGeneralWidgetArray = $dataArray;
            }
            
            return $this->accountGeneralWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

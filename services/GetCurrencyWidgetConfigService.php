<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService};
use app\finders\CurrencyFinder;
use app\forms\ChangeCurrencyForm;

/**
 * Возвращает массив данных для CurrencyWidget
 */
class GetCurrencyWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для CurrencyWidget
     */
    private $currencyWidgetArray = [];
    
    /**
     * Возвращает массив данных для CurrencyWidget
     * @param array $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->currencyWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(CurrencyFinder::class);
                $currencyArray = $finder->find();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                
                ArrayHelper::multisort($currencyArray, 'code');
                $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $currentCurrencyModel = $service->handle();
                
                $dataArray['form'] = new ChangeCurrencyForm([
                    'scenario'=>ChangeCurrencyForm::SET,
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $dataArray['header'] = \Yii::t('base', 'Currency');
                
                $dataArray['template'] = 'currency-form.twig';
                
                $this->currencyWidgetArray = $dataArray;
            }
            
            return $this->currencyWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

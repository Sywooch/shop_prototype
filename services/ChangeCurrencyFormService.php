<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    FrontendTrait,
    GetCurrentCurrencyService};
use app\finders\CurrencyFinder;
use app\forms\ChangeCurrencyForm;

/**
 * Сохраняет изменения текущей валюты
 */
class ChangeCurrencyFormService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для CurrencyWidget
     */
    private $currencyWidgetArray = [];
    
    /**
     * Возвращает форму для смены валюты приложения
     * @param array $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->currencyWidgetArray)) {
                $dataArray = [];
                
                $finder = new CurrencyFinder();
                $currencyArray = $finder->find();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                
                ArrayHelper::multisort($currencyArray, 'code');
                $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
                
                $service = new GetCurrentCurrencyService();
                $currentCurrencyModel = $service->handle();
                
                $dataArray['form'] = new ChangeCurrencyForm([
                    'scenario'=>ChangeCurrencyForm::SET,
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $dataArray['view'] = 'currency-form.twig';
                
                $this->currencyWidgetArray = $dataArray;
            }
            
            return $this->currencyWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

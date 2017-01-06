<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\finders\{CurrencyFinder,
    CurrencyIdFinder};
use app\widgets\{CartWidget,
    CurrencyWidget};

/**
 * Сохраняет изменения текущей валюты
 */
class CurrencySetService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для CurrencyWidget
     */
    private $currencyWidgetArray = [];
    /**
     * @var ChangeCurrencyForm
     */
    private $form = null;
    
    /**
     * Обрабатывает запрос на изменение текущей валюты
     * @param array $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $this->form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]);
            
            if ($request->isPost === true) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        if ($this->setCurrency() !== true) {
                            throw new ErrorException($this->methodError('setCurrency'));
                        }
                        
                        return $this->form->url;
                    }
                }
            }
            
            $dataArray = $this->getCurrencyWidgetArray();
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @return array
     */
    private function getCurrencyWidgetArray(): array
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
                $dataArray['form'] = \Yii::configure($this->form, [
                    'id'=>$this->getCurrencyModel()->id,
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
    
    /**
     * Сохраняет новую основную валюту в сесии
     * @return bool
     */
    private function setCurrency(): bool
    {
        try {
            $finder = new CurrencyIdFinder([
                'id'=>$this->form->id
            ]);
            $currencyModel = $finder->find();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createCurrencyKey(),
                'model'=>$currencyModel
            ]);
            $saver->save();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\{GetCategoriesMenuWidgetConfigService,
    GetCurrentCurrencyModelService,
    GetCurrencyWidgetConfigService,
    GetMailingsFormWidgetConfigService,
    GetSearchWidgetConfigService,
    GetShortCartWidgetConfigService};
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    MailingsFinder,
    PurchasesSessionFinder};
use app\forms\{AbstractBaseForm,
    ChangeCurrencyForm,
    MailingForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы подписки на рассылки
 */
class MailingsIndexRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML 
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                    'key'=>HashHelper::createCartKey()
                ]);
                $ordersCollection = $finder->find();
                
                $finder = \Yii::$app->registry->get(CurrencyFinder::class);
                $currencyArray = $finder->find();
                if (empty($currencyArray)) {
                    throw new ErrorException($this->emptyError('currencyArray'));
                }
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesModelArray = $finder->find();
                if (empty($categoriesModelArray)) {
                    throw new ErrorException($this->emptyError('categoriesModelArray'));
                }
                
                $finder = \Yii::$app->registry->get(MailingsFinder::class);
                $mailingsArray = $finder->find();
                
                $changeCurrencyForm = new ChangeCurrencyForm([
                    'scenario'=>ChangeCurrencyForm::SET,
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $mailingForm = new MailingForm(['scenario'=>MailingForm::SAVE]);
                
                $dataArray = [];
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user);
                
                /*$service = \Yii::$app->registry->get(GetShortCartWidgetConfigService::class);
                $dataArray['shortCartWidgetConfig'] = $service->handle();*/
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                
                /*$service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();*/
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
                
                /*$service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);*/
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                
                /*$service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();*/
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                
                /*$service = \Yii::$app->registry->get(GetMailingsWidgetConfigService::class);
                $dataArray['mailingsWidgetConfig'] = $service->handle();*/
                $dataArray['mailingsWidgetConfig'] = $this->mailingsWidgetConfig($mailingsArray);
                
                /*$service = \Yii::$app->registry->get(GetMailingsFormWidgetConfigService::class);
                $dataArray['mailingsFormWidgetConfig'] = $service->handle();*/
                $dataArray['mailingsFormWidgetConfig'] = $this->mailingsFormWidgetConfig($mailingsArray, $mailingForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета MailingsWidget
     * @param array $mailingsArray
     * @return array
     */
    private function mailingsWidgetConfig(array $mailingsArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['header'] = \Yii::t('base', 'Available mailings');
            $dataArray['template'] = 'mailings.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета MailingsFormWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function mailingsFormWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['form'] = $mailingForm;
            $dataArray['header'] = \Yii::t('base', 'Sign up now!');
            $dataArray['template'] = 'mailings-form.twig';
               
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

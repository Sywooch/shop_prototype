<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\GetCurrentCurrencyModelService;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    PurchasesSessionFinder};
use app\forms\{AbstractBaseForm,
    ChangeCurrencyForm,
    SubscribeForm,
    UserRegistrationForm,
    UserLoginForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на поиск данных для формирования формы регистрации
 */
class UserRegistrationRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Формирует массив данных для рендеринга страницы формы регистрации
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
                if (empty($ordersCollection)) {
                    throw new ErrorException($this->emptyError('ordersCollection'));
                }
                
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
                
                $changeCurrencyForm = new ChangeCurrencyForm([
                    'id'=>$currentCurrencyModel->id,
                    'url'=>Url::current()
                ]);
                
                $userRegistrationForm = new UserRegistrationForm();
                $userLoginForm = new UserLoginForm();
                $subscribeForm = new SubscribeForm();
                
                $dataArray = [];
                
                $dataArray['userRegistrationWidgetConfig'] = $this->userRegistrationWidgetConfig($userRegistrationForm);
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user, $userLoginForm);
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                $dataArray['frontendFooterWidgetConfig'] = $this->frontendFooterWidgetConfig($subscribeForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив настроек для виджета UserRegistrationWidget
     * @param AbstractBaseForm
     * @return array
     */
    public function userRegistrationWidgetConfig(AbstractBaseForm $userRegistrationForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $userRegistrationForm;
            $dataArray['header'] = \Yii::t('base', 'Registration');
            $dataArray['template'] = 'registration-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

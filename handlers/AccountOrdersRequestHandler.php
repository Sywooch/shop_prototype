<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{AccountOrdersFinder,
    OrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\forms\{AbstractBaseForm,
    OrdersFiltersForm,
    PurchaseForm};
use app\helpers\{DateHelper,
    HashHelper};
use app\collections\PaginationInterface;
use app\services\GetCurrentCurrencyModelService;
use app\models\CurrencyInterface;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы с заказами
 */
class AccountOrdersRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                $usersModel = \Yii::$app->user->identity;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(OrdersFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['ordersFilters']])
                ]);
                $filtersModel = $finder->find();
                
                $finder = \Yii::$app->registry->get(AccountOrdersFinder::class, [
                    'id_user'=>$usersModel->id,
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $purchasesCollection = $finder->find();
                
                if ($purchasesCollection->isEmpty() === true) {
                    if ($purchasesCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                
                $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
                $statusesArray = $finder->find();
                if (empty($statusesArray)) {
                    throw new ErrorException($this->emptyError('statusesArray'));
                }
                
                $ordersFiltersForm = new OrdersFiltersForm(array_filter($filtersModel->toArray()));
                $purchaseForm = new PurchaseForm(['scenario'=>PurchaseForm::CANCEL]);
                
                $dataArray = [];
                
                $dataArray['оrdersFiltersWidgetConfig'] = $this->оrdersFiltersWidgetConfig($sortingTypesArray, $statusesArray, $ordersFiltersForm);
                $dataArray['accountOrdersWidgetConfig'] = $this->accountOrdersWidgetConfig($purchasesCollection->asArray(), $purchaseForm, $currentCurrencyModel);
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($purchasesCollection->pagination);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета OrdersFiltersWidget
     * @param array $sortingTypesArray
     * @param array $statusesArray
     * @param AbstractBaseForm $ordersFiltersForm
     * @return array
     */
    private function оrdersFiltersWidgetConfig(array $sortingTypesArray, array $statusesArray, AbstractBaseForm $ordersFiltersForm): array
    {
        try {
            $dataArray = [];
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            asort($statusesArray,SORT_STRING);
            array_unshift($statusesArray, \Yii::$app->params['formFiller']);
            $dataArray['statuses'] = $statusesArray;
            
            if (empty($ordersFiltersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $ordersFiltersForm->sortingType = $key;
                    }
                }
            }
            if (empty($ordersFiltersForm->dateFrom)) {
                $ordersFiltersForm->dateFrom = DateHelper::getToday00();
            }
            if (empty($ordersFiltersForm->dateTo)) {
                $ordersFiltersForm->dateTo = DateHelper::getToday00();
            }
            
            $ordersFiltersForm->url = Url::current();
            
            $dataArray['form'] = $ordersFiltersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'orders-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountOrdersWidget
     * @param array $ordersArray массив PurchasesModel
     * @patram AbstractBaseForm $purchaseForm
     * @return array
     */
    private function accountOrdersWidgetConfig(array $ordersArray, AbstractBaseForm $purchaseForm, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Orders');
            $dataArray['purchases'] = $ordersArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $purchaseForm;
            $dataArray['template'] = 'account-orders.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param PaginationInterface $pagination
     * @return array
     */
    private function paginationWidgetConfig(PaginationInterface $pagination): array
    {
        try {
            $dataArray = [];
            
            $dataArray['pagination'] = $pagination;
            $dataArray['template'] = 'pagination.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait};
use app\finders\{AccountOrdersFinder,
    OrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\forms\{OrdersFiltersForm,
    PurchaseForm};
use app\helpers\{DateHelper,
    HashHelper};
use app\filters\OrdersFiltersInterface;
use app\collections\PaginationInterface;
use app\models\CurrencyInterface;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы с заказами
 */
class AccountOrdersRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait;
    
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
                
                $currentCurrencyModel = $this->getCurrentCurrency();
                
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
                
                $dataArray = [];
                
                $dataArray['оrdersFiltersWidgetConfig'] = $this->оrdersFiltersWidgetConfig($filtersModel);
                $dataArray['accountOrdersWidgetConfig'] = $this->accountOrdersWidgetConfig($purchasesCollection->asArray(), $currentCurrencyModel);
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
     * @param OrdersFiltersInterface $filtersModel
     * @return array
     */
    private function оrdersFiltersWidgetConfig(OrdersFiltersInterface $filtersModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
            $sortingTypesArray = $finder->find();
            if (empty($sortingTypesArray)) {
                throw new ErrorException($this->emptyError('sortingTypesArray'));
            }
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            $finder = \Yii::$app->registry->get(OrderStatusesFinder::class);
            $statusesArray = $finder->find();
            asort($statusesArray,SORT_STRING);
            array_unshift($statusesArray, \Yii::$app->params['formFiller']);
            $dataArray['statuses'] = $statusesArray;
            
            $form = new OrdersFiltersForm(array_filter($filtersModel->toArray()));
            
            if (empty($form->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $form->sortingType = $key;
                    }
                }
            }
            if (empty($form->dateFrom)) {
                $form->dateFrom = DateHelper::getToday00();
            }
            if (empty($form->dateTo)) {
                $form->dateTo = DateHelper::getToday00();
            }
            
            $form->url = Url::current();
            
            $dataArray['form'] = $form;
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
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function accountOrdersWidgetConfig(array $ordersArray, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Orders');
            $dataArray['purchases'] = $ordersArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = new PurchaseForm(['scenario'=>PurchaseForm::CANCEL]);
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

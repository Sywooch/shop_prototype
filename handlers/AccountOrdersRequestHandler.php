<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{AccountOrdersFinder,
    OrdersFiltersSessionFinder,
    OrderStatusesFinder,
    SortingTypesFinder};
use app\forms\{OrdersFiltersForm,
    PurchaseForm};
use app\helpers\HashHelper;
use app\services\GetCurrentCurrencyModelService;

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
                $ordersCollection = $finder->find();
                
                if ($ordersCollection->isEmpty() === true) {
                    if ($ordersCollection->pagination->totalCount > 0) {
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
                $purchaseForm = new PurchaseForm();
                
                $dataArray = [];
                
                $dataArray['оrdersFiltersWidgetConfig'] = $this->оrdersFiltersWidgetConfig($sortingTypesArray, $statusesArray, $ordersFiltersForm);
                $dataArray['accountOrdersWidgetConfig'] = $this->accountOrdersWidgetConfig($ordersCollection->asArray(), $purchaseForm, $currentCurrencyModel);
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($ordersCollection->pagination);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

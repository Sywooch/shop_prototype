<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetCategoriesBreadcrumbsWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetEmptyProductsWidgetConfigService,
    GetFiltersWidgetConfigService,
    GetSearchWidgetConfigService,
    GetPaginationWidgetConfigService,
    GetProductsWidgetConfigService,
    GetUserInfoWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCartWidgetConfigService::class);
                $dataArray['cartWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetProductsCollectionService::class);
                $productsCollection = $service->handle($request);
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    
                    $service = \Yii::$app->registry->get(GetEmptyProductsWidgetConfigService::class);
                    $dataArray['emptyProductsWidgetConfig'] = $service->handle();
                } else {
                    $service = \Yii::$app->registry->get(GetProductsWidgetConfigService::class);
                    $dataArray['productsWidgetConfig'] = $service->handle($request);
                    
                    $service = \Yii::$app->registry->get(GetPaginationWidgetConfigService::class);
                    $dataArray['paginationWidgetConfig'] = $service->handle($request);
                }
                
                $service = \Yii::$app->registry->get(GetCategoriesBreadcrumbsWidgetConfigService::class);
                $dataArray['categoriesBreadcrumbsWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetFiltersWidgetConfigService::class);
                $dataArray['filtersWidgetConfig'] = $service->handle($request);
                
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

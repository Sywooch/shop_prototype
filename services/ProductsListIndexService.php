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
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $service = new GetUserInfoWidgetConfigService();
            $dataArray['userInfoWidgetConfig'] = $service->handle();
            
            $service = new GetCartWidgetConfigService();
            $dataArray['cartWidgetConfig'] = $service->handle();
            
            $service = new GetCurrencyWidgetConfigService();
            $dataArray['currencyWidgetConfig'] = $service->handle();
            
            $service = new GetSearchWidgetConfigService();
            $dataArray['searchWidgetConfig'] = $service->handle($request);
            
            $service = new GetCategoriesMenuWidgetConfigService();
            $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
            
            $service = new GetProductsCollectionService();
            $productsCollection = $service->handle($request);
            
            if ($productsCollection->isEmpty() === true) {
                if ($productsCollection->pagination->totalCount > 0) {
                    throw new NotFoundHttpException($this->error404());
                }
                
                $service = new GetEmptyProductsWidgetConfigService();
                $dataArray['emptyProductsWidgetConfig'] = $service->handle();
            } else {
                $service = new GetProductsWidgetConfigService();
                $dataArray['productsWidgetConfig'] = $service->handle($request);
                
                $service = new GetPaginationWidgetConfigService();
                $dataArray['paginationWidgetConfig'] = $service->handle($request);
            }
            
            $service = new GetCategoriesBreadcrumbsWidgetConfigService();
            $dataArray['categoriesBreadcrumbsWidgetConfig'] = $service->handle($request);
            
            $service = new GetFiltersWidgetConfigService();
            $dataArray['filtersWidgetConfig'] = $service->handle($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

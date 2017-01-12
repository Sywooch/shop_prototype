<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetFiltersWidgetConfigService,
    GetPaginationWidgetConfigService,
    GetProductsCollectionService,
    GetProductsWidgetConfigService};
use app\forms\FiltersForm;
use app\helpers\{HashHelper,
    StringHelper};
use app\savers\SessionModelSaver;
use app\filters\ProductsFilters;
use app\widgets\{EmptyProductsWidget,
    FiltersWidget,
    PaginationWidget,
    ProductsWidget};

/**
 * Сохраняет фильтры каталога товаров
 */
class FiltersSetAjaxService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение товарных фильтров
     * @param array $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new FiltersForm(['scenario'=>FiltersForm::SAVE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $model = new ProductsFilters(['scenario'=>ProductsFilters::SESSION]);
                    $model->sortingField = $form->sortingField;
                    $model->sortingType = $form->sortingType;
                    $model->colors = $form->colors;
                    $model->sizes = $form->sizes;
                    $model->brands = $form->brands;
                    if ($model->validate() === false) {
                        throw new ErrorException($this->modelError($model->errors));
                    }
                    
                    $saver = new SessionModelSaver([
                        'key'=>HashHelper::createFiltersKey($form->url),
                        'model'=>$model
                    ]);
                    $saver->save();
                    
                    $service = \Yii::$app->registry->get(GetProductsCollectionService::class);
                    $productsCollection = $service->handle($request);
                    
                    $dataArray = [];
                    
                    if ($productsCollection->isEmpty() === true) {
                        $service = \Yii::$app->registry->get(GetEmptyProductsWidgetConfigService::class);
                        $emptyProductsWidgetConfig = $service->handle();
                        $dataArray['items'] = EmptyProductsWidget::widget($emptyProductsWidgetConfig);
                    } else {
                        $service = \Yii::$app->registry->get(GetProductsWidgetConfigService::class);
                        $productsWidgetConfig = $service->handle($request);
                        $dataArray['items'] = ProductsWidget::widget($productsWidgetConfig);
                        
                        $service = \Yii::$app->registry->get(GetPaginationWidgetConfigService::class);
                        $paginationWidgetConfig = $service->handle($request);
                        $dataArray['pagination'] = PaginationWidget::widget($paginationWidgetConfig);
                    }
                    
                    $service = \Yii::$app->registry->get(GetFiltersWidgetConfigService::class);
                    $filtersWidgetConfig = $service->handle($request);
                    $dataArray['filters'] = FiltersWidget::widget($filtersWidgetConfig);
                    
                    return $dataArray;
                }
            }
            
            /*if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('request'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $model = new ProductsFilters(['scenario'=>ProductsFilters::SESSION]);
            $model->sortingField = $form->sortingField;
            $model->sortingType = $form->sortingType;
            $model->colors = $form->colors;
            $model->sizes = $form->sizes;
            $model->brands = $form->brands;
            if ($model->validate() === false) {
                throw new ErrorException($this->modelError($model->errors));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createFiltersKey($form->url),
                'model'=>$model
            ]);
            $saver->save();
            
            return StringHelper::cutPage($form->url);*/
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

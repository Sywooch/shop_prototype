<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\AbstractBaseHandler;
use app\services\{AdminProductsCollectionService,
    GetCurrentCurrencyModelService};
use app\finders\{ActiveStatusesFinder,
    AdminProductsFiltersSessionFinder,
    BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    SizesFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategoryIdCategoryFinder};
use app\helpers\HashHelper;
use app\forms\{AdminProductForm,
    AdminProductsFiltersForm};
use app\collections\CollectionInterface;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminProductsRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $service = \Yii::$app->registry->get(AdminProductsCollectionService::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]),
                    'page'=>$request->get(\Yii::$app->params['pagePointer']) ?? 0
                ]);
                $productsCollection = $service->get();
                
                $dataArray = [];
                
                $dataArray['adminProductsFiltersWidgetConfig'] = $this->adminProductsFiltersWidgetConfig();
                $dataArray['adminProductsWidgetConfig'] = $this->adminProductsWidgetConfig($productsCollection);
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection);
                $dataArray['adminCsvProductsFormWidgetConfig'] = $this->adminCsvProductsFormWidgetConfig($productsCollection);
                
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
     * Возвращает массив конфигурации для виджета AdminProductsFiltersWidget
     * @return array
     */
    private function adminProductsFiltersWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']])
            ]);
            $filtersModel = $finder->find();
            
            $finder = \Yii::$app->registry->get(SortingFieldsFinder::class);
            $sortingFieldsArray = $finder->find();
            if (empty($sortingFieldsArray)) {
                throw new ErrorException($this->emptyError('sortingFieldsArray'));
            }
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
            $sortingTypesArray = $finder->find();
            if (empty($sortingTypesArray)) {
                throw new ErrorException($this->emptyError('sortingTypesArray'));
            }
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            $finder = \Yii::$app->registry->get(ColorsFinder::class);
            $colorsArray = $finder->find();
            if (empty($colorsArray)) {
                throw new ErrorException($this->emptyError('colorsArray'));
            }
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            $finder = \Yii::$app->registry->get(SizesFinder::class);
            $sizesArray = $finder->find();
            if (empty($sizesArray)) {
                throw new ErrorException($this->emptyError('sizesArray'));
            }
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            $finder = \Yii::$app->registry->get(BrandsFinder::class);
            $brandsArray = $finder->find();
            if (empty($brandsArray)) {
                throw new ErrorException($this->emptyError('brandsArray'));
            }
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesArray = $finder->find();
            if (empty($categoriesArray)) {
                throw new ErrorException($this->emptyError('categoriesArray'));
            }
            ArrayHelper::multisort($categoriesArray, 'name');
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
            
            $dataArray['subcategory'] = [\Yii::$app->params['formFiller']];
            if (!empty($filtersModel->category)) {
                $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, ['id_category'=>$filtersModel->category]);
                $subcategoryArray = $finder->find();
                if (empty($subcategoryArray)) {
                    throw new ErrorException($this->emptyError('subcategoryArray'));
                }
                $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
                $dataArray['subcategory'] = ArrayHelper::merge($dataArray['subcategory'], $subcategoryArray);
            }
            
            $finder = \Yii::$app->registry->get(ActiveStatusesFinder::class);
            $activeStatusesArray = $finder->find();
            if (empty($activeStatusesArray)) {
                throw new ErrorException($this->emptyError('activeStatusesArray'));
            }
            asort($activeStatusesArray, SORT_STRING);
            $dataArray['activeStatuses'] = $activeStatusesArray;
            
            $form = new AdminProductsFiltersForm($filtersModel->toArray());
            
            if (empty($form->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingField']) {
                        $form->sortingField = $key;
                    }
                }
            }
            
            if (empty($form->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $form->sortingType = $key;
                    }
                }
            }
            
            $form->url = Url::current();
            
            $dataArray['form'] = $form;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'admin-products-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminProductsWidget
     * @param CollectionInterface $productsCollection
     * @return array
     */
    private function adminProductsWidgetConfig(CollectionInterface $productsCollection): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Products');
            
            if ($productsCollection->isEmpty() === true) {
                if ($productsCollection->pagination->totalCount > 0) {
                    throw new NotFoundHttpException($this->error404());
                }
            }
            
            $dataArray['products'] = $productsCollection->asArray();
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $dataArray['currency'] = $service->get();
            
            $dataArray['form'] = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
            $dataArray['template'] = 'admin-products.twig';
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param CollectionInterface $productsCollection
     * @return array
     */
    private function paginationWidgetConfig(CollectionInterface $productsCollection): array
    {
        try {
            $dataArray = [];
            
            $pagination = $productsCollection->pagination;
            
            if (empty($pagination)) {
                throw new ErrorException($this->emptyError('pagination'));
            }
            
            $dataArray['pagination'] = $pagination;
            $dataArray['template'] = 'pagination.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCsvProductsFormWidget
     * @param CollectionInterface $productsCollection
     * @return array
     */
    private function adminCsvProductsFormWidgetConfig(CollectionInterface $productsCollection): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Download selected products in csv format');
            $dataArray['template'] = 'admin-csv-products-form.twig';
            $dataArray['isAllowed'] = $productsCollection->isEmpty() ? false : true;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\GetCurrentCurrencyModelService;
use app\finders\{ActiveStatusesFinder,
    AdminProductsFinder,
    AdminProductsFiltersSessionFinder,
    BrandsFinder,
    CategoriesFinder,
    ColorsFinder,
    SizesFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategoryIdCategoryFinder};
use app\helpers\HashHelper;
use app\forms\{AbstractBaseForm,
    AdminProductForm,
    AdminProductsFiltersForm};
use app\models\CurrencyInterface;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminProductsRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
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
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                    'key'=>HashHelper::createCurrencyKey()
                ]);
                $currentCurrencyModel = $service->get();
                if (empty($currentCurrencyModel)) {
                    throw new ErrorException($this->emptyError('currentCurrencyModel'));
                }
                
                $finder = \Yii::$app->registry->get(AdminProductsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['adminProductsFilters']])
                ]);
                $filtersModel = $finder->find();
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(AdminProductsFinder::class, [
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $productsCollection = $finder->find();
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $finder = \Yii::$app->registry->get(SortingFieldsFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                
                $finder = \Yii::$app->registry->get(ColorsFinder::class);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SizesFinder::class);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                
                $finder = \Yii::$app->registry->get(BrandsFinder::class);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesArray = $finder->find();
                if (empty($categoriesArray)) {
                    throw new ErrorException($this->emptyError('categoriesArray'));
                }
                
                if (!empty($filtersModel->category)) {
                    $finder = \Yii::$app->registry->get(SubcategoryIdCategoryFinder::class, [
                        'id_category'=>$filtersModel->category
                    ]);
                    $subcategoryArray = $finder->find();
                    if (empty($subcategoryArray)) {
                        throw new ErrorException($this->emptyError('subcategoryArray'));
                    }
                }
                
                $finder = \Yii::$app->registry->get(ActiveStatusesFinder::class);
                $activeStatusesArray = $finder->find();
                if (empty($activeStatusesArray)) {
                    throw new ErrorException($this->emptyError('activeStatusesArray'));
                }
                
                $adminProductsFiltersForm = new AdminProductsFiltersForm($filtersModel->toArray());
                $adminProductForm = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
                
                $dataArray = [];
                
                $dataArray['adminProductsFiltersWidgetConfig'] = $this->adminProductsFiltersWidgetConfig($sortingFieldsArray, $sortingTypesArray, $colorsArray, $sizesArray, $brandsArray, $categoriesArray, $subcategoryArray ?? [], $activeStatusesArray, $adminProductsFiltersForm);
                $dataArray['adminProductsWidgetConfig'] = $this->adminProductsWidgetConfig($productsCollection->asArray(), $currentCurrencyModel, $adminProductForm);
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection->pagination);
                $dataArray['adminCsvProductsFormWidgetConfig'] = $this->adminCsvProductsFormWidgetConfig($productsCollection->isEmpty() ? false : true);
                $dataArray['adminAddProductButtonWidgetConfig'] = $this->adminAddProductButtonWidgetConfig();
                
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
     * @param array $sortingFieldsArray
     * @param array $sortingTypesArray
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $brandsArray
     * @param array $subcategoryArray
     * @param array $activeStatusesArray
     * @param AbstractBaseForm $adminProductsFiltersForm
     * @return array
     */
    private function adminProductsFiltersWidgetConfig(array $sortingFieldsArray, array $sortingTypesArray, array $colorsArray, array $sizesArray, array $brandsArray, array $categoriesArray, array $subcategoryArray, array $activeStatusesArray, AbstractBaseForm $adminProductsFiltersForm): array
    {
        try {
            $dataArray = [];
            
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
            ArrayHelper::multisort($categoriesArray, 'name');
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
            
            if (!empty($subcategoryArray)) {
                ArrayHelper::multisort($subcategoryArray, 'name');
                $subcategoryArray = ArrayHelper::map($subcategoryArray, 'id', 'name');
            }
            $dataArray['subcategory'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $subcategoryArray);
            
            asort($activeStatusesArray, SORT_STRING);
            $dataArray['activeStatuses'] = $activeStatusesArray;
            
            if (empty($adminProductsFiltersForm->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingField']) {
                        $adminProductsFiltersForm->sortingField = $key;
                    }
                }
            }
            
            if (empty($adminProductsFiltersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $adminProductsFiltersForm->sortingType = $key;
                    }
                }
            }
            
            $adminProductsFiltersForm->url = Url::current();
            
            $dataArray['form'] = $adminProductsFiltersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'admin-products-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminProductsWidget
     * @param array $productsArray
     * @param CurrencyInterface $currentCurrencyModel
     * @param AbstractBaseForm $adminProductForm
     * @return array
     */
    private function adminProductsWidgetConfig(array $productsArray, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $adminProductForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Products');
            $dataArray['products'] = $productsArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $adminProductForm;
            $dataArray['template'] = 'admin-products.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCsvProductsFormWidget
     * @param bool $isAllowed
     * @return array
     */
    private function adminCsvProductsFormWidgetConfig(bool $isAllowed): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Download selected products in csv format');
            $dataArray['template'] = 'admin-csv-products-form.twig';
            $dataArray['isAllowed'] = $isAllowed;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminAddProductButtonWidget
     */
    private function adminAddProductButtonWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'admin-add-product-button.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

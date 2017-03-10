<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{BrandsFilterSphinxFinder,
    CategoriesFinder,
    ColorsFilterSphinxFinder,
    CurrencyFinder,
    ProductsFiltersSessionFinder,
    ProductsSphinxFinder,
    PurchasesSessionFinder,
    SizesFilterSphinxFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SphinxFinder};
use app\forms\{AbstractBaseForm,
    ChangeCurrencyForm,
    FiltersForm,
    UserLoginForm};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы каталога товаров
 */
class ProductsListSearchRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
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
                $searchText = $request->get(\Yii::$app->params['searchKey']) ?? null;
                if (empty($searchText)) {
                    throw new ErrorException($this->emptyError('searchText'));
                }
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                
                $validator = new StripTagsValidator();
                $searchText = $validator->validate($searchText);
                $page = $validator->validate($page);
                
                $page = filter_var($page, FILTER_VALIDATE_INT);
                if ($page === false) {
                    throw new ErrorException($this->invalidError('page'));
                }
                
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
                
                $finder = \Yii::$app->registry->get(SphinxFinder::class, [
                    'search'=>$searchText
                ]);
                $sphinxArray = $finder->find();
                $sphinxArray = ArrayHelper::getColumn($sphinxArray, 'id');
                
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
                
                $userLoginForm = new UserLoginForm();
                
                $dataArray = [];
                
                if (empty($sphinxArray)) {
                    $dataArray['emptySphinxWidgetConfig'] = $this->emptySphinxWidgetConfig();
                } else {
                    $finder = \Yii::$app->registry->get(ProductsFiltersSessionFinder::class, [
                        'key'=>HashHelper::createFiltersKey(Url::current())
                    ]);
                    $filtersModel = $finder->find();
                    
                    $finder = \Yii::$app->registry->get(ProductsSphinxFinder::class, [
                        'sphinx'=>$sphinxArray,
                        'page'=>$page,
                        'filters'=>$filtersModel
                    ]);
                    $productsCollection = $finder->find();
                    
                    if ($productsCollection->isEmpty() === true) {
                        if ($productsCollection->pagination->totalCount > 0) {
                            throw new NotFoundHttpException($this->error404());
                        }
                        
                        $dataArray['emptyProductsWidgetConfig'] = $this->emptyProductsWidgetConfig();
                    } else {
                        $dataArray['productsWidgetConfig'] = $this->productsWidgetConfig($productsCollection, $currentCurrencyModel);
                        $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection->pagination);
                    }
                    
                    $finder = \Yii::$app->registry->get(ColorsFilterSphinxFinder::class, [
                        'sphinx'=>$sphinxArray
                    ]);
                    $colorsArray = $finder->find();
                    if (empty($colorsArray)) {
                        throw new ErrorException($this->emptyError('colorsArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(SizesFilterSphinxFinder::class, [
                        'sphinx'=>$sphinxArray
                    ]);
                    $sizesArray = $finder->find();
                    if (empty($sizesArray)) {
                        throw new ErrorException($this->emptyError('sizesArray'));
                    }
                    
                    $finder = \Yii::$app->registry->get(BrandsFilterSphinxFinder::class, [
                        'sphinx'=>$sphinxArray
                    ]);
                    $brandsArray = $finder->find();
                    if (empty($brandsArray)) {
                        throw new ErrorException($this->emptyError('brandsArray'));
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
                    
                    $filtersForm = new FiltersForm(array_filter($filtersModel ->toArray()));
                    
                    $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($colorsArray, $sizesArray, $brandsArray, $sortingFieldsArray, $sortingTypesArray, $filtersForm);
                }
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user, $userLoginForm);
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig($searchText);
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
                $dataArray['searchBreadcrumbsWidgetConfig'] = $this->searchBreadcrumbsWidgetConfig($searchText);
                
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
     * Возвращает массив конфигурации для виджета EmptySphinxWidget
     * @return array
     */
    private function emptySphinxWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'paragraph.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $brandsArray
     * @param array $sortingFieldsArray
     * @param array $sortingTypesArray
     * @param AbstractBaseForm $filtersForm
     * @return array
     */
    private function filtersWidgetConfig(array $colorsArray, array $sizesArray, array $brandsArray, array $sortingFieldsArray, array $sortingTypesArray, AbstractBaseForm $filtersForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            if (empty($filtersForm->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingField']) {
                        $filtersForm->sortingField = $key;
                    }
                }
            }
            if (empty($filtersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $filtersForm->sortingType = $key;
                    }
                }
            }
            
            $dataArray['form'] = $filtersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'products-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SearchBreadcrumbsWidget
     * @param string $searchText искомая фраза
     * @return array
     */
    private function searchBreadcrumbsWidgetConfig(string $searchText=''): array
    {
        try {
            $dataArray = [];
            
            $dataArray['text'] = $searchText;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

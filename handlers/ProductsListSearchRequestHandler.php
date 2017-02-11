<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait,
    BaseFrontendHandlerTrait,
    ProductsListHandlerTrait};
use app\finders\{BrandsFilterSphinxFinder,
    ColorsFilterSphinxFinder,
    ProductsSphinxFinder,
    SizesFilterSphinxFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SphinxFinder};
use app\forms\FiltersForm;
use app\filters\ProductsFiltersInterface;

/**
 * Обрабатывает запрос на получение данных 
 * для рендеринга страницы каталога товаров
 */
class ProductsListSearchRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait, BaseFrontendHandlerTrait, ProductsListHandlerTrait;
    
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
                
                $currentCurrencyModel = $this->getCurrentCurrency();
                $filtersModel = $this->getProductsFilters();
                $ordersCollection = $this->getOrdersSessionCollection();
                
                $finder = \Yii::$app->registry->get(SphinxFinder::class, [
                    'search'=>$searchText
                ]);
                $sphinxArray = $finder->find();
                $sphinxArray = ArrayHelper::getColumn($sphinxArray, 'id');
                
                $dataArray = [];
                
                $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig();
                $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
                $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currentCurrencyModel);
                $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig($searchText);
                $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig();
                $dataArray['searchBreadcrumbsWidgetConfig'] = $this->searchBreadcrumbsWidgetConfig($searchText);
                
                if (empty($sphinxArray)) {
                    $dataArray['emptySphinxWidgetConfig'] = $this->emptySphinxWidgetConfig();
                } else {
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
                        $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($productsCollection);
                    }
                    $dataArray['filtersWidgetConfig'] = $this->filtersWidgetConfig($sphinxArray, $filtersModel);
                }
                
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
            
            $dataArray['template'] = 'empty-sphinx.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета FiltersWidget
     * @param array $sphinxArray массив ID найденных записей
     * @param ProductsFiltersInterface $filtersModel
     * @return array
     */
    private function filtersWidgetConfig(array $sphinxArray, ProductsFiltersInterface $filtersModel): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(ColorsFilterSphinxFinder::class, [
                'sphinx'=>$sphinxArray
            ]);
            $colorsArray = $finder->find();
            if (empty($colorsArray)) {
                throw new ErrorException($this->emptyError('colorsArray'));
            }
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            $finder = \Yii::$app->registry->get(SizesFilterSphinxFinder::class, [
                'sphinx'=>$sphinxArray
            ]);
            $sizesArray = $finder->find();
            if (empty($sizesArray)) {
                throw new ErrorException($this->emptyError('sizesArray'));
            }
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            $finder = \Yii::$app->registry->get(BrandsFilterSphinxFinder::class, [
                'sphinx'=>$sphinxArray
            ]);
            $brandsArray = $finder->find();
            if (empty($brandsArray)) {
                throw new ErrorException($this->emptyError('brandsArray'));
            }
            ArrayHelper::multisort($brandsArray, 'brand');
            $dataArray['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
            
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
            
            $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($filtersModel ->toArray())));
            
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
            
            $dataArray['form'] = $form;
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

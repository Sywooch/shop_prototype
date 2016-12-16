<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\services\CommonFrontendService;
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    FiltersSessionFinder,
    ProductsFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SubcategorySeocodeFinder};
use app\helpers\HashHelper;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends CommonFrontendService
{
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            # Общие для всех frontend сервисов данные
            
            $dataArray = parent::handle($request);
            
            # Товарные фильтры
            
            $finder = new FiltersSessionFinder([
                'key'=>HashHelper::createFiltersKey()
            ]);
            $filtersModel = $finder->find();
            if (empty($filtersModel)) {
                throw new ErrorException($this->emptyError('filtersModel'));
            }
            
            # Данные для вывода списка товаров
            
            $finder = new ProductsFinder([
                'category'=>$request[\Yii::$app->params['categoryKey']],
                'subcategory'=>$request[\Yii::$app->params['subcategoryKey']],
                'page'=>$request[\Yii::$app->params['pagePointer']],
                'filters'=>$filtersModel
            ]);
            $productsCollection = $finder->find();
            
            if ($productsCollection->isEmpty() === true) {
                if ($productsCollection->pagination->totalCount > 0) {
                    throw new NotFoundHttpException($this->error404());
                }
                $dataArray['emptyConfig']['view'] = 'empty-products.twig';
            } else {
                $dataArray['productsConfig']['products'] = $productsCollection;
                $dataArray['productsConfig']['currency'] = $dataArray['currencyModel'];
                $dataArray['productsConfig']['view'] = 'products-list.twig';
            }
            
            # Данные для вывода breadcrumbs
            
            if (!empty($category = $request[\Yii::$app->params['categoryKey']])) {
                $finder = new CategorySeocodeFinder([
                    'seocode'=>$category
                ]);
                $categoryModel = $finder->find();
                if (empty($categoryModel)) {
                    throw new ErrorException($this->emptyError('categoryModel'));
                }
                $dataArray['breadcrumbsConfig']['category'] = $categoryModel;
                if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']])) {
                    $finder = new SubcategorySeocodeFinder([
                        'seocode'=>$subcategory
                    ]);
                    $subcategoryModel = $finder->find();
                    if (empty($subcategoryModel)) {
                        throw new ErrorException($this->emptyError('subcategoryModel'));
                    }
                    $dataArray['breadcrumbsConfig']['subcategory'] = $subcategoryModel;
                }
            }
            
            # Данные для вывода фильтров каталога
            
            $finder = new ColorsFilterFinder([
                'category'=>$request[\Yii::$app->params['categoryKey']],
                'subcategory'=>$request[\Yii::$app->params['subcategoryKey']],
            ]);
            $colorsArray = $finder->find();
            if (empty($colorsArray)) {
                throw new ErrorException($this->emptyError('colorsArray'));
            }
            $dataArray['filtersConfig']['colors'] = $colorsArray;
            
            $finder = new SizesFilterFinder([
                'category'=>$request[\Yii::$app->params['categoryKey']],
                'subcategory'=>$request[\Yii::$app->params['subcategoryKey']],
            ]);
            $sizesArray = $finder->find();
            if (empty($sizesArray)) {
                throw new ErrorException($this->emptyError('sizesArray'));
            }
            $dataArray['filtersConfig']['sizes'] = $sizesArray;
            
            $finder = new BrandsFilterFinder([
                'category'=>$request[\Yii::$app->params['categoryKey']],
                'subcategory'=>$request[\Yii::$app->params['subcategoryKey']],
            ]);
            $brandsArray = $finder->find();
            if (empty($brandsArray)) {
                throw new ErrorException($this->emptyError('brandsArray'));
            }
            $dataArray['filtersConfig']['brands'] = $brandsArray;
            
            $finder = new SortingFieldsFinder();
            $sortingFieldsArray = $finder->find();
            if (empty($sortingFieldsArray)) {
                throw new ErrorException($this->emptyError('sortingFieldsArray'));
            }
            $dataArray['filtersConfig']['sortingFields'] = $sortingFieldsArray;
            
            /*$finder = new SortingTypesFinder([
                'collection'=>new SortingTypesCollection()
            ]);
            $sortingTypesCollection = $finder->find();
            if ($sortingTypesCollection->isEmpty()) {
                throw new ErrorException($this->emptyError('sortingTypesCollection'));
            }
            $dataArray['filtersConfig']['sortingTypesCollection'] = $sortingTypesCollection;
            
            $form = new FiltersForm(array_merge(['url'=>Url::current()], !empty($filtersArray) ? $filtersArray : []));
            if (empty($form->sortingField)) {
                $form->sortingField = $sortingFieldsCollection->getDefault();
            }
            if (empty($form->sortingType)) {
                $form->sortingType = $sortingTypesCollection->getDefault();
            }
            $dataArray['filtersConfig']['form'] = $form;
            
            $dataArray['filtersConfig']['view'] = 'products-filters.twig';*/
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

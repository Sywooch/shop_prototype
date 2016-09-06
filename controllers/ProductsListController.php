<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{Url, 
    ArrayHelper};
use app\controllers\AbstractBaseController;
use app\helpers\{MappersHelper, 
    ModelsInstancesHelper};
use app\queries\{GetCategoriesListQuery,
    GetCurrencyListQuery,
    GetProductsListQuery};
use app\models\{BrandsModel,
    CategoriesModel,
    ColorsModel,
    CurrencyModel,
    FiltersModel,
    ProductsModel,
    SearchModel,
    SizesModel,
    SubcategoryModel};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    private $_configSphynx = [
        'tableName'=>'shop',
        'fields'=>['id'],
    ];
    
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $renderArray = array();
            
            $productsQuery = new GetProductsListQuery([
                'fields'=>['id', 'date', 'name', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
                'sortingField'=>'date'
            ]);
            $renderArray['productsList'] = $productsQuery->getQuery()->all();
            
            $categoriesQuery = new GetCategoriesListQuery([
                'fields'=>['id', 'name', 'seocode'],
                'sortingField'=>'name',
                'sortingType'=>SORT_ASC
            ]);
            $renderArray['categoriesList'] = $categoriesQuery->getQuery()->all();
            
            $currencyQuery = new GetCurrencyListQuery([
                'fields'=>['id', 'currency'],
                'sortingField'=>'currency',
                'sortingType'=>SORT_ASC
            ]);
            $renderArray['currencyList'] = $currencyQuery->getQuery()->all();
            
            $colorsQuery = ColorsModel::find()
                ->select(['colors.id', 'colors.color'])
                ->orderBy(['colors.color'=>SORT_ASC])
                ->innerJoin('products_colors', '[[colors.id]]=[[products_colors.id_colors]]')
                ->innerJoin('products', '[[products_colors.id_products]]=[[products.id]]')
                ->where(['products.active'=>true]);
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $colorsQuery->innerJoin('categories', '[[products.id_categories]]=[[categories.id]]');
                }
                if (\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])) {
                    $colorsQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                }
                $colorsQuery->andFilterWhere([
                    'categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                    'subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
                ]);
            $renderArray['colorsList'] = $colorsQuery->all();
            
            $sizesQuery = SizesModel::find()
                ->select(['sizes.id', 'sizes.size'])
                ->orderBy(['sizes.size'=>SORT_ASC])
                ->innerJoin('products_sizes', '[[sizes.id]]=[[products_sizes.id_sizes]]')
                ->innerJoin('products', '[[products_sizes.id_products]]=[[products.id]]')
                ->where(['products.active'=>true]);
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $sizesQuery->innerJoin('categories', '[[products.id_categories]]=[[categories.id]]');
                }
                if (\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])) {
                    $sizesQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                }
                $sizesQuery->andFilterWhere([
                    'categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                    'subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
                ]);
            $renderArray['sizesList'] = $sizesQuery->all();
                
            $brandsQuery = BrandsModel::find()
                ->select(['brands.id', 'brands.brand'])
                ->orderBy(['brands.brand'=>SORT_ASC])
                ->innerJoin('products_brands', '[[brands.id]]=[[products_brands.id_brands]]')
                ->innerJoin('products', '[[products_brands.id_products]]=[[products.id]]')
                ->where(['products.active'=>true]);
                if (\Yii::$app->request->get(\Yii::$app->params['categoryKey'])) {
                    $brandsQuery->innerJoin('categories', '[[products.id_categories]]=[[categories.id]]');
                }
                if (\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])) {
                    $brandsQuery->innerJoin('subcategory', '[[products.id_subcategory]]=[[subcategory.id]]');
                }
                $brandsQuery->andFilterWhere([
                    'categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                    'subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
                ]);
            $renderArray['brandsList'] = $brandsQuery->all();
            
            $renderArray['currencyModel'] = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM]);
            $renderArray['productsModel'] = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM]);
            $renderArray['categoriesModel'] = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_FORM]);
            $renderArray['subcategoryModel'] = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_FORM]);
            $renderArray['searchModel'] = new SearchModel(['scenario'=>SearchModel::GET_FROM_FORM]);
            $renderArray['filtersModel'] = \Yii::$app->filters;
            
            //$renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            
            return $this->render('products-list.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов
     * @return string
     */
    public function actionSearch()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $renderArray = array();
            
            if (!empty($sphynxSearchArray = MappersHelper::getProductsSearch($this->_configSphynx))) {
                $sphynxResult = ArrayHelper::getColumn($sphynxSearchArray, 'id');
                $this->_config['sphynxArray'] = $sphynxResult;
                $this->_config['queryClass'] = 'app\queries\ProductsListSearchQueryCreator';
                $renderArray['productsList'] = MappersHelper::getProductsList($this->_config);
            }
            
            $renderArray['colorsList'] = MappersHelper::getColorsList();
            $renderArray['sizesList'] = MappersHelper::getSizesList();
            $renderArray['brandsList'] = MappersHelper::getBrands();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('products-list.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\GetFiltersForProducts'],
        ];
    }
}

<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{Url, 
    ArrayHelper};
use app\controllers\AbstractBaseController;
use app\helpers\{MappersHelper, 
    ModelsInstancesHelper};
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
    private $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'active', 'total_products'],
        'orderByField'=>'date',
        'getDataSorting'=>false,
    ];
    
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
            
            $productsQuery = ProductsModel::find()
                ->orderBy(['date'=>SORT_DESC])
                ->select(['products.id', 'products.date', 'products.code', 'products.name', 'products.description', 'products.short_description', 'products.price', 'products.images', 'products.id_categories', 'products.id_subcategory', 'products.active', 'products.total_products'])
                ->innerJoinWith('categories')
                ->innerJoinWith('subcategory')
                ->andFilterWhere([
                    'categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey']),
                    'subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subCategoryKey'])
                ])
                ->andWhere(['products.active'=>true])
                ->limit(20);
                foreach (\Yii::$app->params['filterKeys'] as $filter) {
                    if (in_array($filter, array_keys(array_filter(\Yii::$app->filters->attributes)))) {
                        $productsQuery->innerJoin('products_' . $filter, '[[products.id]]=[[products_' . $filter . '.id_products]]');
                        $productsQuery->innerJoin($filter, '[[products_' . $filter . '.id_' . $filter . ']]=[[' . $filter . '.id]]');
                        $productsQuery->andWhere([$filter . '.id'=>\Yii::$app->filters->$filter]);
                    }
                }
            $renderArray['productsList'] = $productsQuery->all();
            
            $renderArray['categoriesList'] = CategoriesModel::find()
                ->select(['categories.id', 'categories.name', 'categories.seocode'])
                ->orderBy(['categories.name'=>SORT_ASC])
                ->with('subcategory')
                ->all();
            
            $renderArray['currencyList'] = CurrencyModel::find()
                ->select(['currency.id', 'currency.currency'])
                ->orderBy(['currency.currency'=>SORT_ASC])
                ->all();
            
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
            ['class'=>'app\filters\ProductsFilter'],
        ];
    }
}

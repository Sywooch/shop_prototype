<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{Url, 
    ArrayHelper};
use app\controllers\AbstractBaseController;
use app\helpers\{MappersHelper, 
    ModelsInstancesHelper};
use app\models\{CategoriesModel,
    CurrencyModel,
    ProductsModel,
    SearchModel,
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
            $renderArray['productsList'] = ProductsModel::find()
                ->orderBy(['date'=>SORT_DESC])
                ->with('categories', 'subcategory')
                ->limit(20)
                ->all();
            
            $renderArray['categoriesList'] = CategoriesModel::find()
                ->orderBy(['name'=>SORT_ASC])
                ->with('subcategory')
                ->all();
            
            $renderArray['currencyList'] = CurrencyModel::find()->all();
            
            $renderArray['currencyModel'] = new CurrencyModel();
            $renderArray['productsModel'] = new ProductsModel();
            $renderArray['categoriesModel'] = new CategoriesModel();
            $renderArray['subcategoryModel'] = new SubcategoryModel();
            $renderArray['searchModel'] = new SearchModel();
            
            //$renderArray['productsList'] = MappersHelper::getProductsList($this->_config);
            //$renderArray['colorsList'] = MappersHelper::getColorsList();
            //$renderArray['sizesList'] = MappersHelper::getSizesList();
            //$renderArray['brandsList'] = MappersHelper::getBrands();
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

<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\MappersHelper;
use app\traits\ExceptionsTrait;
use app\models\{CategoriesModel,
    CurrencyModel,
    ProductsModel,
    SearchModel,
    SubcategoryModel};

/**
 * Обрабатывает запросы, связанные с валютами сайта
 */
class CurrencyController extends AbstractBaseController
{
    use ExceptionsTrait;
    
    /**
     * Обрабатывает запрос на установку валюты
     */
    public function actionSetCurrency()
    {
        try {
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не установлена переменная categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не установлена переменная subCategoryKey!');
            }
            
            $currencyModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FOR_SET_CURRENCY]);
            $categoriesModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_FORM]);
            $subcategoryModel = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_FORM]);
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM]);
            $searchModel = new SearchModel(['scenario'=>SearchModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $currencyModel->load(\Yii::$app->request->post()) && $categoriesModel->load(\Yii::$app->request->post()) && $subcategoryModel->load(\Yii::$app->request->post()) && $productsModel->load(\Yii::$app->request->post()) && $searchModel->load(\Yii::$app->request->post())) {
                if ($currencyModel->validate() && $categoriesModel->validate() && $subcategoryModel->validate() && $productsModel->validate() && $searchModel->validate()) {
                    if (!empty(\Yii::$app->shopUser)) {
                        \Yii::$app->shopUser->currency = MappersHelper::getCurrencyById($currencyModel);
                    }
                    if (!empty($productsModel->id)) {
                        $urlArray = ['product-detail/index', 'categories'=>$categoriesModel->seocode, 'subcategory'=>$subcategoryModel->seocode, 'id'=>$productsModel->id];
                    } elseif (!empty($searchModel->search)) {
                        $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>$searchModel->search];
                    } else {
                        $urlArray = ['products-list/index'];
                        if (!empty($categoriesModel->seocode)) {
                            $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>$categoriesModel->seocode]);
                        }
                        if (!empty($subcategoryModel->seocode)) {
                            $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>$subcategoryModel->seocode]);
                        }
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

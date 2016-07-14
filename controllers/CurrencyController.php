<?php

namespace app\controllers;

use yii\web\Controller;
use yii\helpers\Url;
use app\helpers\MappersHelper;
use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\models\CurrencyModel;

/**
 * Обрабатывает запросы, связанные с валютами сайта
 */
class CurrencyController extends Controller
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
            
            $currencyModel = new CurrencyModel(['scenario'=>CurrencyModel::GET_FROM_FORM_SET]);
            
            if (\Yii::$app->request->isPost && $currencyModel->load(\Yii::$app->request->post())) {
                if ($currencyModel->validate()) {
                    if (!empty(\Yii::$app->user)) {
                        \Yii::$app->user->currency = MappersHelper::getCurrencyModelById($currencyModel);
                    }
                    if (!empty($currencyModel->id_products)) {
                        $urlArray = ['product-detail/index', 'categories'=>$currencyModel->categories, 'subcategory'=>$currencyModel->subcategory, 'id'=>$currencyModel->id_products];
                    } elseif (!empty($currencyModel->search)) {
                        $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>$currencyModel->search];
                    } else {
                        $urlArray = ['products-list/index'];
                        if (!empty($currencyModel->categories)) {
                            $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>$currencyModel->categories]);
                        }
                        if (!empty($currencyModel->subcategory)) {
                            $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>$currencyModel->subcategory]);
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

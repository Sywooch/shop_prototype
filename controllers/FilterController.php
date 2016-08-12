<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\helpers\SessionHelper;
use app\controllers\AbstractBaseController;
use app\models\ProductsModel;

/**
 * Обрабатывает запросы данных, к которым необходимо применить фильтры
 */
class FilterController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на применение фильтров
     * @return redirect
     */
    public function actionAddFilters()
    {
        try {
            if ($this->addFilters()) {
                if (!empty(\Yii::$app->filters->search)) {
                    $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
                } else {
                    $urlArray = ['products-list/index'];
                    if (!empty(\Yii::$app->filters->categories)) {
                        $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>\Yii::$app->filters->categories]);
                    }
                    if (!empty(\Yii::$app->filters->subcategory)) {
                        $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>\Yii::$app->filters->subcategory]);
                    }
                }
                return $this->redirect(Url::to($urlArray));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на применение фильтров для административного раздела
     * @return redirect
     */
    public function actionAddFiltersAdmin()
    {
        try {
            $this->addFiltersAdmin();
            return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на применение фильтров для создания csv файла
     * @return redirect
     */
    public function actionAddFiltersDownloadProducts()
    {
        try {
            \Yii::$app->filters->clean();
            \Yii::$app->filters->cleanAdmin();
            
            $this->addFiltersAdmin();
            
            $_config = [
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'queryClass'=>'app\queries\ProductsListAdminQueryCreator',
                'orderByField'=>'date',
                'getDataSorting'=>false,
            ];
            
            $objectsProductsList = \app\helpers\MappersHelper::getProductsList($_config);
            $productsFile = \app\helpers\CSVHelper::getCSV([
                'path'=>\Yii::getAlias('@app/web/sources/csv/'),
                'filename'=>'products' . time(),
                'objectsArray'=>$objectsProductsList,
                'fields'=>['id', 'date', 'code', 'name', 'short_description', 'price', 'images', 'active', 'total_products'],
            ]);
            
            $response = \Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            return ['productsFile'=>$productsFile];
            
            //return $this->redirect(Url::to(['admin/download-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     * @return redirect
     */
    public function actionCleanFilters()
    {
        try {
            if ($this->cleanFilters()) {
                if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession']])) {
                    throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                }
                if (!empty(\Yii::$app->filters->search)) {
                    $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
                } else {
                    $urlArray = ['products-list/index'];
                    if (!empty(\Yii::$app->filters->categories)) {
                        $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>\Yii::$app->filters->categories]);
                    }
                    if (!empty(\Yii::$app->filters->subcategory)) {
                        $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>\Yii::$app->filters->subcategory]);
                    }
                }
                return $this->redirect(Url::to($urlArray));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров для административного раздела
     * @return redirect
     */
    public function actionCleanFiltersAdmin()
    {
        try {
            if ($this->cleanFilters()) {
                if (!\Yii::$app->filters->cleanAdmin()) {
                    throw new ErrorException('Ошибка при очистке фильтров!');
                }
                if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession'] . '.admin'])) {
                    throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                }
                return $this->redirect(Url::to(['admin/show-products'])); 
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Удаляет данные фильтров
     * @return boolean
     */
    private function cleanFilters()
    {
        try {
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    if (!\Yii::$app->filters->clean()) {
                        throw new ErrorException('Ошибка при очистке фильтров!');
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            return true;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Добавляет данные для фильтрации в \Yii::$app->filters
     * @return boolean
     */
    private function addFilters()
    {
        try {
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            return true;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    private function addFiltersAdmin()
    {
        try {
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_ADMIN_FILTER]);
            
            if ($this->addFilters()) {
                $productsModel->load(\Yii::$app->request->post());
                if (!empty($productsModel->categories)) {
                    \Yii::$app->filters->categories = $productsModel->categories;
                }
                if (!empty($productsModel->subcategory)) {
                    \Yii::$app->filters->subcategory = $productsModel->subcategory;
                }
                \Yii::$app->filters->active = $productsModel->active;
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsListFilter',
                'only'=>['add-filters', 'clean-filters'],
            ],
            [
                'class'=>'app\filters\ProductsListFilterAdmin',
                'only'=>['add-filters-admin', 'clean-filters-admin'],
            ],
            [
                'class'=>'app\filters\ProductsListFilterCSV',
                'only'=>['add-filters-download-products'],
            ],
        ];
    }
}

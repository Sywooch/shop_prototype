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
            $productsModel = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_ADMIN_FILTER]);
            
            if ($this->addFilters()) {
                $productsModel->load(\Yii::$app->request->post());
                if (!empty($productsModel->categories)) {
                    \Yii::$app->filters->categories = $productsModel->categories;
                }
                if (!empty($productsModel->subcategory)) {
                    \Yii::$app->filters->subcategory = $productsModel->subcategory;
                }
            }
            return $this->redirect(Url::to(['admin/show-products']));
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
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession']])) {
                        throw new ErrorException('Ошибка при удалении фильтров из сесии!');
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
                    return true;
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}

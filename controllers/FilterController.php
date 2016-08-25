<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\helpers\{SessionHelper,
    FiltersHelper};
use app\controllers\AbstractBaseController;

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
            $urlArray = ['products-list/index'];
            if (FiltersHelper::addFilters()) {
                if (!empty(\Yii::$app->filters->search)) {
                    $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
                } else {
                    if (!empty(\Yii::$app->filters->categories)) {
                        $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>\Yii::$app->filters->categories]);
                    }
                    if (!empty(\Yii::$app->filters->subcategory)) {
                        $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>\Yii::$app->filters->subcategory]);
                    }
                }
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на применение фильтров для административного раздела
     * @return redirect
     */
    public function actionAddFiltersAdminCategories()
    {
        try {
            FiltersHelper::addFiltersAdminCategories();
            return $this->redirect(Url::to(['admin/show-products']));
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
            FiltersHelper::addFilters();
            return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на применение фильтров для административного раздела подкатегорий
     * @return redirect
     */
    public function actionAddFiltersAdminSubcategory()
    {
        try {
            FiltersHelper::addFilters();
            return $this->redirect(Url::to(['admin/show-add-subcategory']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на применение фильтров для раздела комментариев
     * @return redirect
     */
    public function actionAddFiltersAdminComments()
    {
        try {
            FiltersHelper::addFilters();
            return $this->redirect(Url::to(['admin/show-comments']));
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
            $urlArray = ['products-list/index'];
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    if (FiltersHelper::cleanFilters()) {
                        if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession']])) {
                            throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                        }
                        if (!empty(\Yii::$app->filters->search)) {
                            $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
                        } else {
                            if (!empty(\Yii::$app->filters->categories)) {
                                $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>\Yii::$app->filters->categories]);
                            }
                            if (!empty(\Yii::$app->filters->subcategory)) {
                                $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>\Yii::$app->filters->subcategory]);
                            }
                        }
                        FiltersHelper::cleanOtherFilters();
                    }
                }
            }
            return $this->redirect(Url::to($urlArray));
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
            if (\Yii::$app->request->isPost) {
                if (FiltersHelper::cleanFilters()) {
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession'] . '.admin'])) {
                        throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                    }
                }
            }
            return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров для административного раздела
     * @return redirect
     */
    public function actionCleanFiltersAdminCategories()
    {
        try {
            if (\Yii::$app->request->isPost) {
                if (FiltersHelper::cleanFilters()) {
                    if (!FiltersHelper::cleanOtherFilters()) {
                        throw new ErrorException('Ошибка при очистке фильтров!');
                    }
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession'] . '.admin'])) {
                        throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                    }
                }
            }
        return $this->redirect(Url::to(['admin/show-products']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров для административного раздела
     * @return redirect
     */
    public function actionCleanFiltersAdminSubcategory()
    {
        try {
            if (\Yii::$app->request->isPost) {
                if (FiltersHelper::cleanFilters()) {
                    if (!FiltersHelper::cleanOtherFilters()) {
                        throw new ErrorException('Ошибка при очистке фильтров!');
                    }
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession'] . '.admin.subcategory'])) {
                        throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                    }
                }
            }
            return $this->redirect(Url::to(['admin/show-add-subcategory']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров для раздела комментариев
     * @return redirect
     */
    public function actionCleanFiltersAdminComments()
    {
        try {
            if (\Yii::$app->request->isPost) {
                if (FiltersHelper::cleanFilters()) {
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession'] . '.admin.comments'])) {
                        throw new ErrorException('Ошибка при удалении фильтров из сесии!');
                    }
                }
            }
            return $this->redirect(Url::to(['admin/show-comments']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            [
                'class'=>'app\filters\ProductsFilter',
                'only'=>['add-filters', 'clean-filters'],
            ],
            [
                'class'=>'app\filters\ProductsFilterAdmin',
                'only'=>['add-filters-admin', 'add-filters-admin-categories', 'clean-filters-admin', 'clean-filters-admin-categories'],
            ],
            [
                'class'=>'app\filters\SubcategoryFilterAdmin',
                'only'=>['add-filters-admin-subcategory', 'clean-filters-admin-subcategory'],
            ],
            [
                'class'=>'app\filters\CommentsFilterAdmin',
                'only'=>['add-filters-admin-comments', 'clean-filters-admin-comments'],
            ],
        ];
    }
}

<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{ActiveStatusesFinder,
    CommentsFinder,
    CommentsFiltersSessionFinder,
    SortingFieldsCommentsFinder,
    SortingTypesFinder};
use app\helpers\HashHelper;
use app\forms\{AbstractBaseForm,
    AdminCommentsFiltersForm,
    CommentForm};
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем комментариев
 */
class AdminCommentsRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
                $validate = new StripTagsValidator();
                $page = $validate->validate($page);
                if (filter_var($page, FILTER_VALIDATE_INT) === false) {
                    throw new ErrorException($this->invalidError('page'));
                }
                
                $finder = \Yii::$app->registry->get(CommentsFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['commentsFilters']])
                ]);
                $filtersModel = $finder->find();
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $finder = \Yii::$app->registry->get(CommentsFinder::class, [
                    'page'=>$page,
                    'filters'=>$filtersModel
                ]);
                $commentsCollection = $finder->find();
                if (empty($commentsCollection)) {
                    throw new ErrorException($this->emptyError('commentsCollection'));
                }
                
                if ($commentsCollection->isEmpty() === true) {
                    if ($commentsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                }
                
                $finder = \Yii::$app->registry->get(SortingFieldsCommentsFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                
                $finder = \Yii::$app->registry->get(ActiveStatusesFinder::class);
                $activeStatusesArray = $finder->find();
                if (empty($activeStatusesArray)) {
                    throw new ErrorException($this->emptyError('activeStatusesArray'));
                }
                
                $commentForm = new CommentForm();
                $adminCommentsFiltersForm = new AdminCommentsFiltersForm($filtersModel->toArray());
                
                $dataArray = [];
                
                $dataArray['adminCommentsWidgetConfig'] = $this->adminCommentsWidgetConfig($commentsCollection->asArray(), $commentForm);
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($commentsCollection->pagination);
                $dataArray['adminCommentsFiltersWidgetConfig'] = $this->adminCommentsFiltersWidgetConfig($sortingFieldsArray, $sortingTypesArray, $activeStatusesArray, $adminCommentsFiltersForm);
                $dataArray['adminCsvCommentsFormWidgetConfig'] = $this->adminCsvCommentsFormWidgetConfig($commentsCollection->isEmpty() ? false : true);
                
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
     * Возвращает массив конфигурации для виджета AdminCommentsWidget
     * @param array $commentsModelsArray
     * @param AbstractBaseForm $commentForm
     * @return array
     */
    private function adminCommentsWidgetConfig(array $commentsModelsArray, AbstractBaseForm $commentForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['comments'] = $commentsModelsArray;
            $dataArray['form'] = $commentForm;
            $dataArray['header'] = \Yii::t('base', 'Comments');
            $dataArray['template'] = 'admin-comments.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCommentsFiltersWidget
     * @param array $sortingFieldsArray
     * @param array $sortingTypesArray
     * @param array $activeStatusesArray
     * @param AbstractBaseForm $adminCommentsFiltersForm
     * @return array
     */
    private function adminCommentsFiltersWidgetConfig(array $sortingFieldsArray, array $sortingTypesArray, array $activeStatusesArray, AbstractBaseForm $adminCommentsFiltersForm): array
    {
        try {
            $dataArray = [];
            
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            asort($activeStatusesArray, SORT_STRING);
            $dataArray['activeStatuses'] = ArrayHelper::merge([''=>\Yii::t('base', 'All')], $activeStatusesArray);
            
            if (empty($adminCommentsFiltersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $adminCommentsFiltersForm->sortingType = $key;
                    }
                }
            }
            
            $adminCommentsFiltersForm->url = Url::current();
            
            $dataArray['form'] = $adminCommentsFiltersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'admin-comments-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCsvCommentsFormWidget
     * @param bool $isAllowed
     * @return array
     */
    private function adminCsvCommentsFormWidgetConfig(bool $isAllowed): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Download selected comments in csv format');
            $dataArray['template'] = 'admin-csv-comments-form.twig';
            $dataArray['isAllowed'] = $isAllowed;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

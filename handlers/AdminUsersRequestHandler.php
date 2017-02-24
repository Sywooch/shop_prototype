<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{SortingFieldsUsersFinder,
    SortingTypesFinder,
    UsersFinder,
    UsersFiltersSessionFinder};
use app\forms\{AbstractBaseForm,
    UsersFiltersForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminUsersRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            $page = $request->get(\Yii::$app->params['pagePointer']) ?? 0;
            
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(UsersFinder::class, [
                    'page'=>$page
                ]);
                $usersCollection = $finder->find();
                
                $finder = \Yii::$app->registry->get(SortingFieldsUsersFinder::class);
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                
                $finder = \Yii::$app->registry->get(SortingTypesFinder::class);
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                
                $finder = \Yii::$app->registry->get(UsersFiltersSessionFinder::class, [
                    'key'=>HashHelper::createHash([\Yii::$app->params['usersFilters']])
                ]);
                $filtersModel = $finder->find();
                
                $usersFiltersForm = new UsersFiltersForm(array_filter($filtersModel->toArray()));
                
                $dataArray = [];
                
                $dataArray['adminUsersWidgetConfig'] = $this->adminUsersWidgetConfig($usersCollection->asArray());
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($usersCollection->pagination);
                $dataArray['usersFiltersWidgetConfig'] = $this->usersFiltersWidgetConfig($sortingFieldsArray, $sortingTypesArray, $usersFiltersForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminUsersWidget
     * @param array $usersModelArray
     * @return array
     */
    private function adminUsersWidgetConfig(array $usersModelArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['users'] = $usersModelArray;
            $dataArray['header'] = \Yii::t('base', 'Users');
            $dataArray['template'] = 'admin-users.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UsersFiltersWidget
     * @param array $sortingFieldsArray
     * @param array $sortingTypesArray
     * @param AbstractBaseForm $usersFiltersForm
     * @return array
     */
    private function usersFiltersWidgetConfig(array $sortingFieldsArray, array $sortingTypesArray, AbstractBaseForm $usersFiltersForm): array
    {
        try {
            $dataArray = [];
            
            asort($sortingFieldsArray, SORT_STRING);
            $dataArray['sortingFields'] = $sortingFieldsArray;
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            if (empty($usersFiltersForm->sortingField)) {
                foreach ($sortingFieldsArray as $key=>$val) {
                    if ($key === 'id') {
                        $usersFiltersForm->sortingField = $key;
                    }
                }
            }
            if (empty($usersFiltersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $usersFiltersForm->sortingType = $key;
                    }
                }
            }
            
            $usersFiltersForm->url = Url::current();
            
            $dataArray['form'] = $usersFiltersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'users-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

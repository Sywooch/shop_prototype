<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\UsersFinder;

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
                
                $dataArray = [];
                
                $dataArray['adminUsersWidgetConfig'] = $this->adminUsersWidgetConfig($usersCollection->asArray());
                $dataArray['paginationWidgetConfig'] = $this->paginationWidgetConfig($usersCollection->pagination);
                
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
}

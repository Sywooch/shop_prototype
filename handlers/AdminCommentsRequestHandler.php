<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{CommentsFinder,
    CommentsFiltersSessionFinder};
use app\helpers\HashHelper;
use app\forms\{AbstractBaseForm,
    CommentForm};

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
                
                $commentForm = new CommentForm();
                
                $dataArray = [];
                
                $dataArray['adminCommentsWidgetConfig'] = $this->adminCommentsWidgetConfig($commentsCollection->asArray(), $commentForm);
                
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
}

<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetProductDetailModelService};
use app\finders\CommentsProductFinder;

/**
 * Возвращает массив данных для CommentsWidget
 */
class GetCommentsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для CommentsWidget
     */
    private $commentsWidgetArray = [];
    
    /**
     * Возвращает массив данных для CommentsWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if (empty($this->commentsWidgetArray)) {
                $dataArray = [];
                
                $service = new GetProductDetailModelService();
                $productsModel = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(CommentsProductFinder::class, ['product'=>$productsModel]);
                $commentsArray = $finder->find();
                
                if (!empty($commentsArray)) {
                    ArrayHelper::multisort($commentsArray, 'date', SORT_DESC);
                    $dataArray['comments'] = $commentsArray;
                }
                
                $dataArray['view'] = 'comments.twig';
                
                $this->commentsWidgetArray = $dataArray;
            }
            
            return $this->commentsWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

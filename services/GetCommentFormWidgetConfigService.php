<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use app\services\{AbstractBaseService,
    GetProductDetailModelService};
use app\forms\CommentForm;

/**
 * Возвращает массив данных для CommentFormWidget
 */
class GetCommentFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для CommentFormWidget
     */
    private $commentFormWidgetArray = [];
    
    /**
     * Возвращает массив данных для CommentFormWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            if (empty($this->commentFormWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductDetailModelService::class);
                $productsModel = $service->handle($request);
                
                $dataArray['form'] = new CommentForm([
                    'scenario'=>CommentForm::SAVE,
                    'id_product'=>$productsModel->id,
                ]);
                $dataArray['template'] = 'comment-form.twig';
                
                $this->commentFormWidgetArray = $dataArray;
            }
            
            return $this->commentFormWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

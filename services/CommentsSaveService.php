<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\{NotFoundHttpException,
    Request,
    Response};
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\CommentForm;
use app\finders\CommentsProductFinder;
use app\models\CommentsModel;
use app\services\{EmailGetSaveEmailService,
    NameGetSaveNameService};
use app\savers\ModelSaver;

/**
 * Сохраняет новый комментарий
 */
class CommentsSaveService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для CommentsWidget
     */
    private $commentsWidgetArray = [];
    /**
     * @var CommentForm
     */
    private $form = null;
    
    /**
     * Обрабатывает запрос на сохранение нового комментария
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $this->form = new CommentForm(['scenario'=>CommentForm::SAVE]);
            
            if ($request->isAjax === true) {
                if ($this->form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($this->form);
                }
            }
            
            if ($request->isPost === true) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        $transaction  = \Yii::$app->db->beginTransaction();
                        
                        try {
                            $service = new EmailGetSaveEmailService();
                            $emailsModel = $service->handle(['email'=>$this->form->email]);
                            
                            $service = new NameGetSaveNameService();
                            $namesModel = $service->handle(['name'=>$this->form->name]);
                            
                            $rawCommentsModel = new CommentsModel();
                            $rawCommentsModel->date = time();
                            $rawCommentsModel->text = $this->form->text;
                            $rawCommentsModel->id_name = $namesModel->id;
                            $rawCommentsModel->id_email = $emailsModel->id;
                            $rawCommentsModel->id_product = $this->form->id_product;
                            
                            $saver = new ModelSaver([
                                'model'=>$rawCommentsModel
                            ]);
                            $saver->save();
                            
                            $transaction->commit();
                            
                            return Url::to(['/product-detail/index', \Yii::$app->params['productKey']=>$this->form->seocode]);
                        } catch (\Throwable $t) {
                            $transaction->rollBack();
                            throw $t;
                        }
                    }
                }
            }
            
            $dataArray = $this->getCommentsWidgetArray($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CommentsWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getCommentsWidgetArray(Request $request): array
    {
        try {
            if (empty($this->commentsWidgetArray)) {
                $dataArray = [];
                
                $productsModel = $this->getProductsModel($request);
                
                $finder = new CommentsProductFinder([
                    'product'=>$productsModel,
                ]);
                $commentsArray = $finder->find();
                
                if (!empty($commentsArray)) {
                    ArrayHelper::multisort($commentsArray, 'date', SORT_DESC);
                    $dataArray['comments'] = $commentsArray;
                }
                
                $dataArray['form'] = \Yii::configure($this->form, [
                    'id_product'=>$productsModel->id,
                    'seocode'=>$productsModel->seocode,
                ]);
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

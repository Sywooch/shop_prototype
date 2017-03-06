<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\AdminMailingForm;
use app\models\MailingsModel;
use app\removers\MailingsModelRemover;
use app\widgets\AdminMailingsWidget;
use app\finders\{AdminMailingsFinder,
    MailingIdFinder};

/**
 * Обрабатывает запрос на удаление данных о форме оплаты
 */
class AdminMailingDeleteRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Обновляет данные о форме оплаты
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminMailingForm(['scenario'=>AdminMailingForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(MailingIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $mailingsModel = $finder->find();
                        if (empty($mailingsModel)) {
                            throw new ErrorException($this->emptyError('mailingsModel'));
                        }
                        
                        $mailingsModel->scenario = MailingsModel::DELETE;
                        if ($mailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($mailingsModel->errors));
                        }
                        
                        $remover = new MailingsModelRemover([
                            'model'=>$mailingsModel
                        ]);
                        $remover->remove();
                        
                        $finder = \Yii::$app->registry->get(AdminMailingsFinder::class);
                        $mailingsModelArray = $finder->find();
                        
                        $mailingsForm = new AdminMailingForm();
                        
                        $adminMailingsWidgetConfig = $this->adminMailingsWidgetConfig($mailingsModelArray, $mailingsForm);
                        $response = AdminMailingsWidget::widget($adminMailingsWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

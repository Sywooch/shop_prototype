<?php

namespace app\controllers;

use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\models\{EmailsModel,
    MailingListModel};
use app\helpers\{ModelsInstancesHelper,
    MappersHelper};

/**
 * Управляет новостными рассылками
 */
class NewsletterController extends AbstractBaseController
{
    /**
     * Управляет процессом подписки на рассылки
     * @return string
     */
    public function actionSubscribe()
    {
        try {
            $renderArray = array();
            $renderArray['emailsModel'] = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $renderArray['mailingListModel'] = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('subscribe.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом оповещения о удачном завершении процесса подписки
     * @return string
     */
    public function actionSubscribeOk()
    {
        try {
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $mailingListModel = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $mailingListModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $mailingListModel->validate()) {
                    if ($emailsModelFromDb = MappersHelper::getEmailsByEmail($emailsModel)) {
                        $emailsModel = $emailsModelFromDb;
                    } else {
                        if (!MappersHelper::setEmailsInsert($emailsModel)) {
                            throw new ErrorException('Ошибка при сохранении E-mail!');
                        }
                    }
                    if (!MappersHelper::setEmailsMailingListInsert($emailsModel, $mailingListModel)) {
                        throw new ErrorException('Ошибка при сохранении связи email с подписками на рассылки!');
                    }
                }
            } else {
                return $this->redirect(Url::to(['newsletter/subscribe']));
            }
            
            $renderArray = array();
            foreach ($mailingListModel->idFromForm as $id) {
                $renderArray['mailingList'][] = MappersHelper::getMailingListById(new MailingListModel(['id'=>$id]));
            }
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('thank.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

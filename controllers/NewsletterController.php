<?php

namespace app\controllers;

use yii\helpers\{Url,
    ArrayHelper};
use app\controllers\AbstractBaseController;
use app\models\{EmailsModel,
    MailingListModel};
use app\helpers\{ModelsInstancesHelper,
    MappersHelper,
    MailHelper,
    HashHelper};

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
            $renderArray['mailingList'] = $renderArray['mailingListModel']->allMailingList;
            
            if (!empty(\Yii::$app->shopUser->id_emails) && !empty(\Yii::$app->shopUser->id)) {
                if ($currentSubscribes = MappersHelper::getMailingListForEmail(new EmailsModel(['email'=>\Yii::$app->shopUser->emails->email]))) {
                    if ($notExists = array_diff(ArrayHelper::getColumn($renderArray['mailingList'], 'id'), ArrayHelper::getColumn($currentSubscribes, 'id'))) {
                        $mailingList = [];
                        $currentMailingList = [];
                        foreach ($renderArray['mailingList'] as $object) {
                            if (in_array($object->id, $notExists)) {
                                $mailingList[] = $object;
                                continue;
                            }
                            $currentMailingList[] = $object;
                        }
                        $renderArray['mailingList'] = $mailingList;
                        $renderArray['currentMailingList'] = $currentMailingList;
                    } else {
                        $renderArray['currentMailingList'] = $renderArray['mailingList'];
                        $renderArray['mailingList'] = [];
                    }
                }
                $renderArray['emailsModel']->email = \Yii::$app->shopUser->emails->email;
            }
            
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
                    
                    if ($currentSubscribes = MappersHelper::getMailingListForEmail(new EmailsModel(['email'=>$emailsModel->email]))) {
                        if (!$arrayDiff = array_diff($mailingListModel->idFromForm, ArrayHelper::getColumn($currentSubscribes, 'id'))) {
                            return $this->redirect(Url::to(['newsletter/subscription-exists']));
                        }
                        $mailingListModel->idFromForm = $arrayDiff;
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
            if (!MailHelper::send([
                [
                    'template'=>'@app/views/mail/subscribe-ok.twig', 
                    'setFrom'=>['admin@shop.com'=>'Shop'], 
                    'setTo'=>['timofey@localhost.localdomain'=>'John Doe'], 
                    'setSubject'=>'Hello!', 
                    'dataForTemplate'=>[
                        'mailingList'=>$renderArray['mailingList'], 
                        'emailsModel'=>$renderArray['emailsModel'], 
                        //'hash'=>HashHelper::createHash([$emailsModel->email, implode('', ArrayHelper::getColumn($renderArray['mailingList'], 'name')), \Yii::$app->params['hashSalt']]),
                        'hash'=>HashHelper::createHash([$emailsModel->email, \Yii::$app->params['hashSalt']]),
                    ],
                ]
            ])) {
                throw new ErrorException('Ошибка при отправке E-mail сообщения!');
            }
            return $this->render('thank.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом отписки
     * @return string
     */
    public function actionUnsubscribe()
    {
        try {
            $renderArray = array();
            $renderArray['mailingList'] = MappersHelper::getMailingListForEmail(new EmailsModel(['email'=>\Yii::$app->request->get('email')]));
            
            //$hashArray = [];
            if (\Yii::$app->request->isGet && !empty(\Yii::$app->request->get('email')) && !empty(\Yii::$app->request->get('hash'))) {
                //$hashArray[] = \Yii::$app->request->get('email');
                //$hashArray[] = implode('', ArrayHelper::getColumn($renderArray['mailingList'], 'name'));
                //$hashArray[] = \Yii::$app->params['hashSalt'];
                $hash = HashHelper::createHash([\Yii::$app->request->get('email'), \Yii::$app->params['hashSalt']]);
                if ($hash != \Yii::$app->request->get('hash')) {
                    return $this->render('error-unsubscribe.twig');
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $renderArray['mailingListModel'] = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('unsubscribe.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Информирует о том, что подписка на текущий канал уже существует
     * @return string
     */
    public function actionSubscriptionExists()
    {
        try {
            $renderArray = array();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('subscription-exists.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}

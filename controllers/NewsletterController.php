<?php

namespace app\controllers;

use yii\helpers\{Url,
    ArrayHelper};
use yii\base\ErrorException;
use yii\db\Transaction;
use app\controllers\AbstractBaseController;
use app\models\{EmailsModel,
    MailingListModel,
    EmailsMailingListModel};
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
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $mailingListModel = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
            $subscriptions = $mailingListModel->allMailingList;
            
            if (!empty(\Yii::$app->shopUser->id_emails) && !empty(\Yii::$app->shopUser->id)) {
                if ($currentSubscriptions = MappersHelper::getMailingListForEmail(new EmailsModel(['email'=>\Yii::$app->shopUser->emails->email]))) {
                    if ($notSubscribed = array_diff(ArrayHelper::getColumn($subscriptions, 'id'), ArrayHelper::getColumn($currentSubscriptions, 'id'))) {
                        $futureSubscriptions = [];
                        $currentSubscriptions = [];
                        foreach ($subscriptions as $object) {
                            if (in_array($object->id, $notSubscribed)) {
                                $futureSubscriptions[] = $object;
                                continue;
                            }
                            $currentSubscriptions[] = $object;
                        }
                        $subscriptions = $futureSubscriptions;
                    } else {
                        $currentSubscriptions = $subscriptions;
                        $subscriptions = [];
                    }
                }
                $emailsModel->email = \Yii::$app->shopUser->emails->email;
            }
            
            $renderArray = array();
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray['mailingListModel'] = $mailingListModel;
            $renderArray['futureSubscriptions'] = $subscriptions;
            $renderArray['currentSubscriptions'] = $currentSubscriptions;
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
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    if ($emailsModelFromDb = MappersHelper::getEmailsByEmail($emailsModel)) {
                        $emailsModel = $emailsModelFromDb;
                    } else {
                        if (!MappersHelper::setEmailsInsert($emailsModel)) {
                            throw new ErrorException('Ошибка при сохранении E-mail!');
                        }
                    }
                    
                    if ($currentSubscribes = MappersHelper::getMailingListForEmail($emailsModel)) {
                        if (!$arrayDiff = array_diff($mailingListModel->idFromForm, ArrayHelper::getColumn($currentSubscribes, 'id'))) {
                            return $this->redirect(Url::to(['newsletter/subscription-exists']));
                        }
                        $mailingListModel->idFromForm = $arrayDiff;
                    }
                    if (!MappersHelper::setEmailsMailingListInsert($emailsModel, $mailingListModel)) {
                        throw new ErrorException('Ошибка при сохранении связи email с подписками на рассылки!');
                    }
                    
                    $transaction->commit();
                }
            } else {
                return $this->redirect(Url::to(['newsletter/subscribe']));
            }
            
            $renderArray = array();
            $renderArray['mailingList'] = $mailingListModel->getObjectsFromIdFromForm();
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
                        'hash'=>HashHelper::createHash([$emailsModel->email, \Yii::$app->params['hashSalt']]),
                    ],
                ]
            ])) {
                throw new ErrorException('Ошибка при отправке E-mail сообщения!');
            }
            return $this->render('subscribe-ok.twig', $renderArray);
        } catch (\Exception $e) {
            if (\Yii::$app->request->isPost) {
                $transaction->rollBack();
            }
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
            if (\Yii::$app->request->isGet && !empty(\Yii::$app->request->get('email')) && !empty(\Yii::$app->request->get('hash'))) {
                $hash = HashHelper::createHash([\Yii::$app->request->get('email'), \Yii::$app->params['hashSalt']]);
                if ($hash != \Yii::$app->request->get('hash')) {
                    return $this->render('error-unsubscribe.twig', ModelsInstancesHelper::getInstancesArray());
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $renderArray = array();
            $renderArray['mailingList'] = MappersHelper::getMailingListForEmail(new EmailsModel(['email'=>\Yii::$app->request->get('email')]));
            $renderArray['emailsModel'] = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM, 'email'=>\Yii::$app->request->get('email')]);
            $renderArray['mailingListModel'] = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('unsubscribe.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом оповещения о удачном завершении процесса отписки
     * @return string
     */
    public function actionUnsubscribeOk()
    {
        try {
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $mailingListModel = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM_REQUIRE]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $mailingListModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $mailingListModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    if (empty($mailingListModel->idFromForm)) {
                        throw new ErrorException('Отсутствуют необходимые данные!');
                    }
                    $emailsMailingList = [];
                    foreach ($mailingListModel->getObjectsFromIdFromForm() as $mailingListObject) {
                        $emailsMailingList[] = new EmailsMailingListModel(['id_email'=>$emailsModel->id, 'id_mailing_list'=>$mailingListObject->id]);
                    }
                    if (!MappersHelper::setEmailsMailingListDelete($emailsMailingList)) {
                        throw new ErrorException('Ошибка при удалении данных из БД!');
                    }
                    
                    $transaction->commit();
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $renderArray = array();
            $renderArray['mailingList'] = $mailingListModel->getObjectsFromIdFromForm();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('unsubscribe-ok.twig', $renderArray);
        } catch (\Exception $e) {
            if (\Yii::$app->request->isPost) {
                $transaction->rollBack();
            }
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

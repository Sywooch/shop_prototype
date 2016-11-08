<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\db\Transaction;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\helpers\{InstancesHelper,
    MailHelper};
use app\models\{EmailsMailingsModel,
    EmailsModel,
    MailingsModel};

/**
 * Коллекция сервис-методов MailingController
 */
class MailingControllerHelper extends AbstractControllerHelper
{
    /**
     * @var object EmailsModel
     */
    private static $_rawEmailsModel;
    /**
     * @var object MailingsModel
     */
    private static $_rawMailingsModel;
    /**
     * @var array массив новых подписок
     * @see MailingControllerHelper::indexPost()
     */
    private static $_sentMail = [];
    /**
     * @var array массив существующих подписок
     * @see MailingControllerHelper::indexPost()
     */
    private static $_currentSubscribes = [];
    
    /**
     * Конструирует данные для MailingController::actionIndex()
     * @return array
     */
    public static function indexGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            self::models();
            
            $renderArray['emailsModel'] = self::$_rawEmailsModel;
            $renderArray['mailingListModel'] = self::$_rawMailingsModel;
            $renderArray['mailingList'] = self::mailingMap(true);
            $renderArray['sentMail'] = self::$_sentMail;
            $renderArray['currentSubscribes'] = self::$_currentSubscribes;
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает данные POST запроса для MailingController::actionIndex()
     */
    public static function indexPost()
    {
        try {
            self::models();
            
            if (self::$_rawEmailsModel->load(\Yii::$app->request->post()) && self::$_rawMailingsModel->load(\Yii::$app->request->post())) {
                if (self::$_rawEmailsModel->validate() && self::$_rawMailingsModel->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        self::saveCheckEmail(self::$_rawEmailsModel);
                        $emailsModel = self::getEmail(self::$_rawEmailsModel->email, false);
                        
                        $diff = EmailsMailingsModel::batchInsert($emailsModel, self::$_rawMailingsModel);
                        if (!empty($diff)) {
                            $subscribesArray = self::getMailings($diff, true);
                            
                            $sent = MailHelper::send([
                                [
                                    'template'=>'@theme/mail/mailing-mail.twig', 
                                    'from'=>['admin@shop.com'=>'Shop'], 
                                    'to'=>['timofey@localhost'=>'Timofey'], 
                                    'subject'=>\Yii::t('base', 'Your subscription to shop.com'), 
                                    'templateData'=>[
                                        'subscribes'=>$subscribesArray ?? false,
                                    ],
                                ]
                            ]);
                            if ($sent < 1) {
                                throw new ErrorException(ExceptionsTrait::methodError('MailHelper::send'));
                            }
                            
                            $transaction->commit();
                            
                            self::$_sentMail = $subscribesArray;
                            
                            return;
                        } else {
                            self::$_currentSubscribes = self::getMailings(self::$_rawMailingsModel['id'], true);
                        }
                        
                        $transaction->rollBack();
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует модели
     */
    private static function models()
    {
        try {
            if (empty(self::$_rawEmailsModel)) {
                self::$_rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ADD_SUBSCRIBER]);
            }
            if (empty(self::$_rawMailingsModel)) {
                self::$_rawMailingsModel = new MailingsModel(['scenario'=>MailingsModel::GET_FROM_ADD_SUBSCRIBER]);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив MailingsModel
     * @param bool $asArray нужно ли возвратить данные как массив
     * @return array
     */
    private static function mailingMap(bool $asArray=false): array
    {
        try {
            $mailingsArray = self::getMailings([], $asArray);
            $mailingsArray = ArrayHelper::map($mailingsArray, 'id', 'name');
            asort($mailingsArray, SORT_STRING);
            
            return $mailingsArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs']
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/mailing/index'], 'label'=>\Yii::t('base', 'Subscribe')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

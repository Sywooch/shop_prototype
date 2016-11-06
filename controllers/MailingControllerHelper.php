<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\helpers\InstancesHelper;
use app\models\{EmailsModel,
    MailingListModel};
use app\validators\EmailExistsCreateValidator;

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
     * @var object MailingListModel
     */
    private static $_rawMailingListModel;
    
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
            $renderArray['mailingListModel'] = self::$_rawMailingListModel;
            $renderArray['mailingList'] = self::getMailingList();
            
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
            
            if (self::$_rawEmailsModel->load(\Yii::$app->request->post()) && self::$_rawMailingListModel->load(\Yii::$app->request->post())) {
                if (self::$_rawEmailsModel->validate() && self::$_rawMailingListModel->validate()) {
                    self::saveEmail(self::$_rawEmailsModel);
                    $emailsQuery = EmailsModel::find();
                    $emailsQuery->extendSelect(['id', 'email']);
                    $emailsQuery->where(['[[emails.email]]'=>self::$_rawEmailsModelReg->email]);
                    $emailsModel = $emailsQuery->one();
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
            if (empty(self::$_rawMailingListModel)) {
                self::$_rawMailingListModel = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_ADD_SUBSCRIBER]);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных MailingListModel
     * @return array
     */
    private static function getMailingList(): array
    {
        try {
            $mailingListQuery = MailingListModel::find();
            $mailingListQuery->extendSelect(['id', 'name']);
            $mailingListQuery->asArray();
            $mailingListArray = $mailingListQuery->all();
            $mailingListArray = ArrayHelper::map($mailingListArray, 'id', 'name');
            asort($mailingListArray, SORT_STRING);
            
            return $mailingListArray;
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

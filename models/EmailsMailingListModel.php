<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{EmailsModel,
    MailingListModel};

/**
 * Представляет данные таблицы emails_mailing_list
 */
class EmailsMailingListModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'emails_mailing_list';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_email', 'id_mailing_list'],
            self::GET_FROM_FORM=>['id_email', 'id_mailing_list'],
        ];
    }
    
    /**
     * Выполняет пакетное сохранение
     * @param object $rawMailingListModel экземпляр MailingListModel
     * @param object $emailsModel экземпляр EmailsModel
     * @return array
     */
    public static function batchInsert(MailingListModel $rawMailingListModel, EmailsModel $emailsModel)
    {
        try {
            $tmailsMailingListList = self::find()->extendSelect(['id_mailing_list'])->where(['[[emails_mailing_list.id_email]]'=>$emailsModel->id])->all();
            $diff = array_diff($rawMailingListModel->id, ArrayHelper::getColumn($tmailsMailingListList, 'id_mailing_list'));
            if (!empty($diff)) {
                $toRecord = [];
                foreach ($diff as $mailingListId) {
                    $toRecord[] = [$emailsModel->id, $mailingListId];
                }
                if (!\Yii::$app->db->createCommand()->batchInsert('emails_mailing_list', ['id_email', 'id_mailing_list'], $toRecord)->execute()) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsMailingListModel::batchInsert']));
                }
            }
            
            return $diff;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

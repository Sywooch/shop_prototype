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
    
    /**
     * Выполняет пакетное сохранение
     * @param object $mailingListModel экземпляр MailingListModel
     * @param object $emailsModel экземпляр EmailsModel
     * @return array
     */
    public static function batchInsert(MailingListModel $mailingListModel, EmailsModel $emailsModel): array
    {
        try {
            $emailsMailingListQuery = self::find();
            $emailsMailingListQuery->extendSelect(['id_mailing_list']);
            $emailsMailingListQuery->where(['[[emails_mailing_list.id_email]]'=>$emailsModel['id']]);
            $emailsMailingListQuery->asArray();
            $emailsMailingListList = $emailsMailingListQuery->all();
            
            $diff = array_diff($mailingListModel['id'], ArrayHelper::getColumn($emailsMailingListList, 'id_mailing_list'));
            if (!empty($diff)) {
                $toRecord = [];
                foreach ($diff as $mailingListId) {
                    $toRecord[] = [$emailsModel['id'], $mailingListId];
                }
                if (!\Yii::$app->db->createCommand()->batchInsert('{{emails_mailing_list}}', ['[[id_email]]', '[[id_mailing_list]]'], $toRecord)->execute()) {
                    throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsMailingListModel::batchInsert']));
                }
            }
            
            return $diff;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

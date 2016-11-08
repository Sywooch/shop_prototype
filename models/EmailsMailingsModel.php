<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use app\models\AbstractBaseModel;
use app\exceptions\ExceptionsTrait;
use app\models\{EmailsModel,
    MailingsModel};

/**
 * Представляет данные таблицы emails_mailings
 */
class EmailsMailingsModel extends AbstractBaseModel
{
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'emails_mailings';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Выполняет пакетное сохранение
     * @param object $emailsModel экземпляр EmailsModel
     * @param object $mailingsModel экземпляр MailingsModel
     * @return array
     */
    public static function batchInsert(EmailsModel $emailsModel, MailingsModel $mailingsModel): array
    {
        try {
            if (!empty($emailsModel->id) && is_array($mailingsModel->id) && !empty($mailingsModel->id)) {
            
                $emailsMailingsQuery = self::find();
                $emailsMailingsQuery->extendSelect(['id_mailing']);
                $emailsMailingsQuery->where(['[[emails_mailings.id_email]]'=>$emailsModel['id']]);
                $emailsMailingsQuery->asArray();
                $emailsMailingsArray = $emailsMailingsQuery->all();
                
                $diff = array_diff($mailingsModel['id'], ArrayHelper::getColumn($emailsMailingsArray, 'id_mailing'));
                if (!empty($diff)) {
                    $toRecord = [];
                    foreach ($diff as $mailingListId) {
                        $toRecord[] = [$emailsModel['id'], $mailingListId];
                    }
                    if (!\Yii::$app->db->createCommand()->batchInsert('{{emails_mailings}}', ['[[id_email]]', '[[id_mailing]]'], $toRecord)->execute()) {
                        throw new ErrorException(ExceptionsTrait::methodError('EmailsMailingsModel::batchInsert'));
                    }
                }
                
            }
            
            return $diff ?? [];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}

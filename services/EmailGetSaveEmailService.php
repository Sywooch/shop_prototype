<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\EmailsModel;
use app\finders\EmailEmailFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект EmailsModel, 
 * при необходимости создает  и сохраняет новый
 */
class EmailGetSaveEmailService extends AbstractBaseService
{
    /**
     * @var EmailsModel
     */
    private $emailsModel = null;
    /**
     * @var string
     */
    private $email = null;
    
    /**
     * Возвращает EmailsModel по email
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @return EmailsModel
     */
    public function get(): EmailsModel
    {
        try {
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->emailsModel)) {
                $emailsModel = $this->getEmail();
                
                if ($emailsModel === null) {
                    $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::SAVE]);
                    $rawEmailsModel->email = $this->email;
                    if ($rawEmailsModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawEmailsModel->errors));
                    }
                    
                    $saver = new ModelSaver([
                        'model'=>$rawEmailsModel
                    ]);
                    $saver->save();
                    
                    $emailsModel = $this->getEmail();
                    
                    if ($emailsModel === null) {
                        throw new ErrorException($this->emptyError('emailsModel'));
                    }
                }
                
                $this->emailsModel = $emailsModel;
            }
            
            return $this->emailsModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает EmailsModel из СУБД
     * @return mixed
     */
    private function getEmail()
    {
        try {
            $finder = \Yii::$app->registry->get(EmailEmailFinder::class, [
                'email'=>$this->email
            ]);
            $emailsModel = $finder->find();
            
            return $emailsModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение EmailGetSaveEmailService::email
     * @param string $email
     */
    public function setEmail(string $email)
    {
        try {
            $this->email = $email;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

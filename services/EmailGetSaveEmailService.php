<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\EmailsModel;
use app\finders\EmailEmailFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект EmailsModel
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
     * Возвращает EmailsModel по Email
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return EmailsModel
     */
    public function handle($request): EmailsModel
    {
        try {
            if (empty($request['email'])) {
                throw new ErrorException($this->emptyError('request'));
            }
            
            $this->email = $request['email'];
            
            if (empty($this->emailsModel)) {
                $emailsModel = $this->getEmail();
                
                if ($emailsModel === null) {
                    $rawEmailsModel = new EmailsModel();
                    $rawEmailsModel->email = $this->email;
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
            $finder = new EmailEmailFinder([
                'email'=>$this->email,
            ]);
            $emailsModel = $finder->find();
            
            return $emailsModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

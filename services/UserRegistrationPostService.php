<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    FrontendTrait,
    RegistrationEmailService};
use app\forms\UserRegistrationForm;
use app\finders\EmailEmailFinder;
use app\savers\ModelSaver;
use app\models\{EmailsModel,
    UsersModel};

/**
 * Формирует массив данных для рендеринга страницы формы регистрации
 */
class UserRegistrationPostService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для UserRegistrationSuccessWidget
     */
    private $userRegistrationSuccessArray = [];
    /**
     * @var UserRegistrationForm
     */
    private $form = null;
    /**
     * @var string email регистрируемого пользователя
     */
    private $email = null;
    
    /**
     * Обрабатывает запрос на сохранение данных нового пользователя
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $this->form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
            
            if ($request->isPost !== true) {
                throw new ErrorException($this->invalidError('request'));
            }
            if ($this->form->load($request->post()) !== true) {
                throw new ErrorException($this->invalidError('post'));
            }
            if ($this->form->validate() !== true) {
                throw new ErrorException($this->modelError($this->form->errors));
            }
            
            $dataArray = [];
            
            $transaction  = \Yii::$app->db->beginTransaction();
            
            try {
                $this->email = $this->form->email;
                
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
                
                $rawUsersModel = new UsersModel(['scenario'=>UsersModel::SAVE]);
                $rawUsersModel->id_email = $emailsModel->id;
                $rawUsersModel->password = password_hash($this->form->password, PASSWORD_DEFAULT);
                $rawUsersModel->validate();
                $saver = new ModelSaver([
                    'model'=>$rawUsersModel
                ]);
                $saver->save();
                
                $mailService = new RegistrationEmailService();
                $mailService->handle(['email'=>$this->email]);
                
                $transaction->commit();
            } catch (\Throwable $t) {
                $transaction->rollBack();
                throw $t;
            }
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $dataArray['successConfig'] = $this->getUserRegistrationSuccessArray();
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserRegistrationSuccessWidget
     * @return array
     */
    private function getUserRegistrationSuccessArray(): array
    {
        try {
            if (empty($this->userRegistrationSuccessArray)) {
                $dataArray = [];
                
                $dataArray['view'] = 'registration-success.twig';
                
                $this->userRegistrationSuccessArray = $dataArray;
            }
            
            return $this->userRegistrationSuccessArray;
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

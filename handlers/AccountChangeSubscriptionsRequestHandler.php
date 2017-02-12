<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\{MailingsEmailFinder,
    MailingsNotEmailFinder};
use app\forms\MailingForm;

/**
 * Обрабатывает запрос на данные 
 * для рендеринга страницы с данными о подписках
 */
class AccountChangeSubscriptionsRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->dataArray)) {
                $usersModel = \Yii::$app->user->identity;
                $email = $usersModel->email->email;
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                    'email'=>$email
                ]);
                $mailingsArray = $finder->find();
                
                $finder = \Yii::$app->registry->get(MailingsNotEmailFinder::class, [
                    'email'=>$email
                ]);
                $notMailingsArray = $finder->find();
                
                $mailingForm = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE_ACC]);
                $notMailingForm = new MailingForm(['scenario'=>MailingForm::SAVE_ACC]);
                
                $dataArray = [];
                
                $dataArray['accountMailingsUnsubscribeWidgetConfig'] = $this->accountMailingsUnsubscribeWidgetConfig($mailingsArray, $mailingForm);
                $dataArray['accountMailingsFormWidgetConfig'] = $this->accountMailingsFormWidgetConfig($notMailingsArray, $notMailingForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

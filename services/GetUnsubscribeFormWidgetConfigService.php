<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\MailingForm;
use app\finders\MailingsEmailFinder;

/**
 * Возвращает массив данных для UnsubscribeFormWidget
 */
class GetUnsubscribeFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array данные для UnsubscribeFormWidget
     */
    private $unsubscribeFormWidgetArray = [];
    
    /**
     * Возвращает массив данных для UnsubscribeFormWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $email = $request->get(\Yii::$app->params['emailKey']);
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->unsubscribeFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new MailingForm([
                    'scenario'=>MailingForm::UNSUBSCRIBE,
                    'email'=>$email,
                ]);
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, ['email'=>$email]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['view'] = 'unsubscribe-form.twig';
                
                $this->unsubscribeFormWidgetArray = $dataArray;
            }
            
            return $this->unsubscribeFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

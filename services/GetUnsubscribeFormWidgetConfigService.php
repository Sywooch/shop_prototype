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
            $unsubscribeKey = $request->get(\Yii::$app->params['unsubscribeKey']);
            $email = $request->get(\Yii::$app->params['emailKey']);
            
            if (empty($unsubscribeKey)) {
                throw new ErrorException($this->emptyError('unsubscribeKey'));
            }
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->unsubscribeFormWidgetArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new MailingForm([
                    'scenario'=>MailingForm::UNSUBSCRIBE,
                    'email'=>$email,
                    'key'=>$unsubscribeKey
                ]);
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, ['email'=>$email]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['template'] = 'unsubscribe-form.twig';
                
                $this->unsubscribeFormWidgetArray = $dataArray;
            }
            
            return $this->unsubscribeFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\MailingForm;

/**
 * Возвращает массив данных для UnsubscribeEmptyWidget
 */
class GetUnsubscribeEmptyWidgetConfigPostService extends AbstractBaseService
{
    /**
     * @var array данные для UnsubscribeEmptyWidget
     */
    private $mailingsUnsubscribeEmptyWidgetArray = [];
    
    /**
     * Возвращает массив данных для UnsubscribeEmptyWidget
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE]);
            
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            $email = $form->email;
            
            if (empty($this->mailingsUnsubscribeEmptyWidgetArray)) {
                $dataArray = [];
                
                $dataArray['email'] = $email;
                $dataArray['view'] = 'unsubscribe-empty.twig';
                
                $this->mailingsUnsubscribeEmptyWidgetArray = $dataArray;
            }
            
            return $this->mailingsUnsubscribeEmptyWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

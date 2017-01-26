<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsFinder;
use app\forms\MailingForm;

/**
 * Возвращает массив конфигурации для виджета MailingsFormWidget
 */
class GetMailingsFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета MailingsFormWidget
     */
    private $mailingsFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета MailingsFormWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->mailingsFormWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(MailingsFinder::class);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['form'] = new MailingForm(['scenario'=>MailingForm::SAVE]);
                
                $dataArray['header'] = \Yii::t('base', 'Sign up now!');
                
                $dataArray['template'] = 'mailings-form.twig';
                
                $this->mailingsFormWidgetArray = $dataArray;
            }
            
            return $this->mailingsFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

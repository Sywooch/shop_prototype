<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsFinder;
use app\forms\MailingForm;

/**
 * Возвращает массив конфигурации для виджета MailingsWidget
 */
class GetMailingsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета MailingsWidget
     */
    private $mailingsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета MailingsWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->mailingsWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(MailingsFinder::class);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['form'] = new MailingForm(['scenario'=>MailingForm::SAVE]);
                
                $dataArray['view'] = 'mailings-form.twig';
                
                $this->mailingsWidgetArray = $dataArray;
            }
            
            return $this->mailingsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

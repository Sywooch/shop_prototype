<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsIdFinder;

/**
 * Возвращает массив конфигурации для виджета MailingsSuccessWidget
 */
class GetMailingsSuccessWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета MailingsSuccessWidget
     */
    private $mailingsSuccessWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета MailingsSuccessWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $diffIdArray = $request['diffIdArray'] ?? null;
            
            if (empty($diffIdArray)) {
                throw new ErrorException($this->emptyError('request'));
            }
            
            if (empty($this->mailingsSuccessWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(MailingsIdFinder::class, ['id'=>$diffIdArray]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['view'] = 'mailings-success.twig';
                
                $this->mailingsSuccessWidgetArray = $dataArray;
            }
            
            return $this->mailingsSuccessWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

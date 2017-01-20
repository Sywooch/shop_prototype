<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsIdFinder;

/**
 * Возвращает массив конфигурации для виджета UnsubscribeSuccessWidget
 */
class GetUnsubscribeSuccessWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UnsubscribeSuccessWidget
     */
    private $unsubscribeSuccessWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UnsubscribeSuccessWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $mailingsIdArray = $request['mailingsIdArray'] ?? null;
            
            if (empty($mailingsIdArray)) {
                throw new ErrorException($this->emptyError('request'));
            }
            
            if (empty($this->unsubscribeSuccessWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(MailingsIdFinder::class, ['id'=>$mailingsIdArray]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['view'] = 'unsubscribe-success.twig';
                
                $this->unsubscribeSuccessWidgetArray = $dataArray;
            }
            
            return $this->unsubscribeSuccessWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

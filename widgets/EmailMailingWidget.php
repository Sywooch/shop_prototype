<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с информацией об успешной подписке
 */
class EmailMailingWidget extends AbstractBaseWidget
{
    /**
     * @var array MailingsModel
     */
    private $mailings;
    /**
     * @var string уникальный ключ, который будет добавлен к ссылке
     */
    public $key;
    /**
     * @var string email, который будет добавлен к ссылке
     */
    public $email;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->mailings)) {
                throw new ErrorException($this->emptyError('mailings'));
            }
            if (empty($this->key)) {
                throw new ErrorException($this->emptyError('key'));
            }
            if (empty($this->email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Hello! This is information about your subscriptions!');
            $renderArray['text'] = \Yii::t('base', 'You successfully subscribed to mailings:');
            
            foreach ($this->mailings as $mailing) {
                $set = [];
                $set['name'] = $mailing->name;
                $set['description'] = $mailing->description;
                $renderArray['mailings'][] = $set;
            }
            
            $renderArray['unsubscribeText'] = \Yii::t('base', 'If you wish to unsubscribe, click here');
            $renderArray['unsubscribeHref'] =Url::to(['/mailings/unsubscribe', \Yii::$app->params['unsubscribeKey']=>$this->key, 'email'=>$this->email], true);
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array MailingsModel свойству MailingsSuccessWidget::mailings
     * @param $mailings array MailingsModel
     */
    public function setMailings(array $mailings)
    {
        try {
            $this->mailings = $mailings;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

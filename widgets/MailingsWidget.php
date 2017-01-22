<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку со списком доступных рассылок
 */
class MailingsWidget extends AbstractBaseWidget
{
    /**
     * @var array MailingsModel
     */
    private $mailings;
    /**
     * @var string заголовок
     */
    public $header;
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
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->mailings)) {
                foreach ($this->mailings as $mailing) {
                    $set = [];
                    $set['name'] = $mailing->name;
                    $set['description'] = $mailing->description;
                    $renderArray['mailings'][] = $set;
                }
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array MailingsModel свойству MailingsWidget::mailings
     * @param array $mailings
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

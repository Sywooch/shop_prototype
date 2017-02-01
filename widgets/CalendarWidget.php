<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractBaseWidget;

/**
 * Формирует HTML строку с календарем
 */
class CalendarWidget extends AbstractBaseWidget
{
    /**
     * @var DateTime, созданный из переданных данных
     */
    private $period;
    /**
     * @var string имя HTML шаблона
     */
    private $template;
    
    public function run()
    {
        try {
            if (empty($this->period)) {
                throw new ErrorException($this->emptyError('period'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = sprintf('%s %s', $this->getMonthVerb(), $this->getYear());
            
            $period = clone $this->period;
            
            $renderArray['calendarHref'] = Url::to(['/calendar/get']);
            
            $prevMonth = $period->modify('-1 month');
            $renderArray['prevTimestamp'] = $prevMonth->getTimestamp();
            
            $nextMonth = $period->modify('+2 month');
            $renderArray['nextTimestamp'] = $nextMonth->getTimestamp();
            
            $renderArray['allowNext'] =((int) $this->getMonth() === (int) (new \DateTime())->format('m')) ? false : true;
            
            $renderArray['dayNames'] = $this->getDayNames();
            
            $renderArray['month'] = $this->getCalendar();
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив данных для формирования календаря
     * @returb array
     */
    private function getCalendar(): array
    {
        try {
            $dataArray = [];
            $days = $this->getDaysInMonth();
            $week = [];
            
            $now = new \DateTime();
            
            for ($day=1; $day <= 7; $day++) {
                if ((int) $this->period->format('N') === $day) {
                    $week[] = [
                        'today'=>($this->period->format('d') === $now->format('d') && $this->period->format('m') === $now->format('m')) ? true : false,
                        'number'=>$this->period->format('d'), 
                        'timestamp'=>$this->period->getTimestamp(),
                        'format'=>\Yii::$app->formatter->asDate($this->period->getTimestamp())
                    ];
                    $this->period->modify('+1 day');
                    $days--;
                } else {
                    $week[] = '';
                }
                
                if ($day === 7 || $days === 0) {
                    if (count($week) < 7) {
                        $filler = array_fill(0, 7 - count($week), '');
                        $week = array_merge($week, $filler);
                    }
                    $dataArray[] = $week;
                    $week = [];
                    $day = 0;
                }
                
                if ($days === 0) {
                    break;
                }
            }
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив с заголовками дней недели
     * @return array
     */
    public function getDayNames()
    {
        try {
            return [
                \Yii::t('base', 'Mon'),
                \Yii::t('base', 'Tue'),
                \Yii::t('base', 'Wed'),
                \Yii::t('base', 'Thu'),
                \Yii::t('base', 'Fri'),
                \Yii::t('base', 'Sat'),
                \Yii::t('base', 'Sun'),
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает номер года
     * @return int
     */
    public function getYear(): int
    {
        try {
            return $this->period->format('Y');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает номер месяца
     * @return int
     */
    public function getMonth(): int
    {
        try {
            return $this->period->format('m');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает название месяца
     * @return string
     */
    public function getMonthVerb(): string
    {
        try {
            return $this->period->format('F');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает количество дней в месяце
     * @return int
     */
    public function getDaysInMonth(): int
    {
        try {
            return $this->period->format('t');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает порядковый номер дня недели первого числа месяца
     * @return int
     */
    public function getRunningDay(): int
    {
        try {
            return $this->period->format('N');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает DateTime свойству CalendarWidget::period
     * @param DateTime $period
     */
    public function setPeriod(\DateTime $period)
    {
        try {
            $this->period = $period;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству CalendarWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}

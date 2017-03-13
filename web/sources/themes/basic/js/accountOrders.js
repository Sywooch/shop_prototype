$(function() {
    
    function AccountCalendar() {
        Calendar.call(this);
    };
    AccountCalendar.prototype = Object.create(Calendar.prototype);
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /* 
     * Запрашивает календарь
    */
    $('div.orders-filters').on('click', '[class^="calendar-href"]', function(event) {
        (new AccountCalendar()).send(event, 'p.calendar-place');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму отменяющую заказ
     */
    $('li').on('click', 'form[id^="order-cancellation-form"] > input:submit', function(event) {
        (new Send()).htmlLiRemoveSend(event, 'span.account-order-status');
        event.preventDefault();
    });
    
});

$(function() {
    
    function AccountCalendar() {
        Calendar.call(this);
    };
    AccountCalendar.prototype = Object.create(Calendar.prototype);
    
    var accountCalendar = new AccountCalendar();
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Запрашивает календарь
    */
    $('div.orders-filters').on('click', '[class^="calendar-href"]', function(event) {
        accountCalendar.send(event, 'p.calendar-place');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму отменяющую заказ
     */
    $('li').on('click', 'form[id^="order-cancellation-form"] > input:submit', function(event) {
        send.htmlLiRemoveSend(event, 'span.account-order-status');
        event.preventDefault();
    });
    
});

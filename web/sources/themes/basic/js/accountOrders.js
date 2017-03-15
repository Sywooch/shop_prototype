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
    $('.orders-filters').on('click', '[class^="calendar-href"]', function(event) {
        accountCalendar.send(event, '.calendar-place');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму отменяющую заказ
     */
    $('.account-orders').find('li').on('click', 'input[type="submit"]', function(event) {
        send.htmlLiRemoveSend(event, '.account-order-status');
        event.preventDefault();
    });
    
});

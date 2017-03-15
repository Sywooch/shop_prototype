$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /*
     * Отправляет форму, обновляющую данные о подписках
     */
    $('.account-unsubscribe, .account-subscribe').on('click', 'input[type="submit"]', function(event) {
        send.htmlArraySend(event, '.account-unsubscribe', '.account-subscribe', 'unsubscribe', 'subscribe');
        event.preventDefault();
    });
    
});

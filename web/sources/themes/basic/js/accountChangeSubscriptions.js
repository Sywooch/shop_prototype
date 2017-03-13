$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /*
     * Отправляет форму, обновляющую данные о подписках
     */
    $('div.account-unsubscribe, div.account-subscribe').on('click', 'input:submit', function(event) {
        (new Send()).htmlArraySend(event, 'div.account-unsubscribe', 'div.account-subscribe', 'unsubscribe', 'subscribe');
        event.preventDefault();
    });
    
});

$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /*
     * Отправляет форму, обновляющую данные пользователя
     */
    $('form').on('click', 'input:submit', function(event) {
        send.htmlTimeoutSend(event, 'div.account-change-password-success', true);
        event.preventDefault();
    });
    
});

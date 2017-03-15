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
    $('#change-data-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.account-change-data-success', false, false, true);
        event.preventDefault();
    });
    
});

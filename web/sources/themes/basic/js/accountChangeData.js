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
        send.htmlSend(event, 'div.account-change-data-success', false, false, true);
        event.preventDefault();
    });
    
});

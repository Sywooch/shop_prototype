$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /*
     * Отправляет форму, обновляющую данные пользователя
     */
    $('form').on('click', 'input:submit', function(event) {
        (new Send()).htmlTimeoutSend(event, 'div.account-change-data-success');
        event.preventDefault();
    });
    
});

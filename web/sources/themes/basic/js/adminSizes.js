$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Отправляет запрос на добавление размера
    */
    $('#size-create-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-sizes', true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление размера
    */
    $('div.admin-sizes').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-sizes', false, true);
        event.preventDefault();
    });
    
});

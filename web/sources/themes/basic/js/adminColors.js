$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Отправляет запрос на добавление цвета
    */
    $('#color-create-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-colors', true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление цвета
    */
    $('div.admin-colors').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-colors', false, true);
        event.preventDefault();
    });
    
});

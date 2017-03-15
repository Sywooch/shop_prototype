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
    $('#size-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-sizes', true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление размера
    */
    $('.admin-sizes').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-sizes', false, true);
        event.preventDefault();
    });
    
});

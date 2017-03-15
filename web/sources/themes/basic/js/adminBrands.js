$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    var send = new Send();
    
    /* 
     * Отправляет запрос на добавление бренда
    */
    $('#brand-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-brands', true);
        event.preventDefault();
    });
    
    /* 
     * Отправляет запрос на удаление бренда
    */
    $('.admin-brands').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-brands', false, true);
        event.preventDefault();
    });
    
});

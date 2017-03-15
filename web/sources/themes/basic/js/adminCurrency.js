$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Отправялет запрос на добавление валюты
     */
    $('#currency-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-currency', true);
        event.preventDefault();
    });
    
    var adminCurrency = $('.admin-currency');
    
    /*
     * Отправляет запрос на удаление валюты
     */
    adminCurrency.on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-currency', false, true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на изменение базовой валюты
     */
    adminCurrency.on('change', 'input[type="checkbox"]', function(event) {
        $('form[id^="admin-currency-delete-form"]').find('input[type="submit"]').attr('disabled', true);
        send.htmlSend(event, '.admin-currency', false, true);
        event.preventDefault();
    });
});

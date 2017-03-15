$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Отправляет запрос на создание новой подписки
     */
     $('#mailing-create-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-mailings', true);
        event.preventDefault();
    });
    
    var adminMailings = $('.admin-mailings');
    
    /*
     * Отправляет запрос на удаление рассылки
     */
    adminMailings.on('click', 'form[id^="admin-mailing-delete-form"] > input[type="submit"]', function(event) {
        send.htmlSend(event, '.admin-mailings', false, true);
        event.preventDefault();
    });
    
    /*
     * Запрашивает форму редактирования
     */
    adminMailings.on('click', 'form[id^="admin-mailing-get-form"] > input[type="submit"]', function(event) {
        send.htmlLiToggleSend(event, '.admin-mailing-previous-data');
        event.preventDefault();
    });
    
    /*
     * Отменяет форму редактирования
     */
    adminMailings.on('click', 'input[type="submit"][name="cancel"]', function(event) {
        send.removeForm(event, '.admin-mailing-previous-data', '.admin-mailing-edit-form');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму с отредактированными данными
     */
    adminMailings.on('click', 'input[type="submit"][name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
});

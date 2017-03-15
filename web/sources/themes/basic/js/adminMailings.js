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
     $('#mailing-create-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-mailings', true);
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на удаление рассылки
     */
    $('div.admin-mailings').on('click', 'form[id^="admin-mailing-delete-form"] > input:submit', function(event) {
        send.htmlSend(event, 'div.admin-mailings', false, true);
        event.preventDefault();
    });
    
    /*
     * Запрашивает форму редактирования
     */
    $('div.admin-mailings').on('click', 'form[id^="admin-mailing-get-form"] > input:submit', function(event) {
        send.htmlLiToggleSend(event, 'div.admin-mailing-previous-data');
        event.preventDefault();
    });
    
    /*
     * Отменяет форму редактирования
     */
    $('div.admin-mailings').on('click', ':submit[name="cancel"]', function(event) {
        send.removeForm(event, 'div.admin-mailing-previous-data', 'div.admin-mailing-edit-form');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму с отредактированными данными
     */
    $('div.admin-mailings').on('click', ':submit[name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
});

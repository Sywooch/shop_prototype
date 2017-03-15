$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Запрос на создание новой формы доставки
     */
    $('#delivery-create-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'div.admin-deliveries', true);
        event.preventDefault();
    });
    
    /*
     * Запрос удаления формы доставки
     */
    $('div.admin-deliveries').on('click', 'form[id^="admin-delivery-delete-form"] > input:submit', function(event) {
        send.htmlSend(event, 'div.admin-deliveries', false, true);
        event.preventDefault();
    });
    
    /*
     * Запрос формы редактирования
     */
    $('div.admin-deliveries').on('click', 'form[id^="admin-delivery-get-form"]', function(event) {
        send.htmlLiToggleSend(event, 'div.admin-delivery-previous-data');
        event.preventDefault();
    });
    
    /*
     * Закрыть форму редактирования
     */
    $('div.admin-deliveries').on('click', ':submit[name="cancel"]', function(event) {
        send.removeForm(event, 'div.admin-delivery-previous-data', 'div.admin-delivery-edit-form');
        event.preventDefault();
    });
    
    /*
     * Запрос изменения формы доставки
     */
    $('div.admin-deliveries').on('click', ':submit[name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
});

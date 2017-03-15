$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    var li = $('.admin-comments').find('li');
    
    /*
     * Запрашивает форму для редактирования комментария
     */
    li.on('click', 'form[id^="admin-comment-detail-get-form"] > input[type="submit"]', function(event) {
        send.htmlLiToggleSend(event, '.admin-comment-previous-data');
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на удаление комментария
     */
    li.on('click', 'form[id^="admin-comment-detail-delete-form"] > input[type="submit"]', function(event) {
        var name = $(event.target).closest('.admin-comment-previous-data').find('a').text();
        var result = confirm('Delete comment on the ' + name + '?');
        if (result == true) {
            send.htmlLiToggleSend(event, '.admin-comment-previous-data');
        }
        event.preventDefault();
    });
    
    /*
     * Отправляет форму с правками
     */
    li.on('click', 'input[type="submit"][name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
    
    /*
     * Отменяет отправку формы
     */
    li.on('click', 'input[type="submit"][name="cancel"]', function(event) {
        send.removeForm(event, '.admin-comment-previous-data', '.admin-comment-edit-form');
        event.preventDefault();
    });
    
    /*
     * Запрашивает данные в формате CSV
     */
    $('#admin-scv-comments-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.csv-success');
        event.preventDefault();
    });
});

$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Запрашивает форму для редактирования комментария
     */
    $('li').on('click', 'form[id^="admin-comment-detail-get-form"] > input:submit', function(event) {
        send.htmlLiToggleSend(event, 'div.admin-comment-previous-data');
        event.preventDefault();
    });
    
    /*
     * Отправляет запрос на удаление комментария
     */
    $('li').on('click', 'form[id^="admin-comment-detail-delete-form"] > input:submit', function(event) {
        var name = $(event.target).closest('div.admin-comment-previous-data').find('a').text();
        var result = confirm('Delete comment on the ' + name + '?');
        if (result == true) {
            send.htmlLiToggleSend(event, 'div.admin-comment-previous-data');
        }
        event.preventDefault();
    });
    
    /*
     * Отправляет форму с правками
     */
    $('li').on('click', ':submit[name="send"]', function(event) {
        send.htmlLiSend(event);
        event.preventDefault();
    });
    
    /*
     * Отменяет отправку формы
     */
    $('li').on('click', ':submit[name="cancel"]', function(event) {
        send.removeForm(event, 'div.admin-comment-previous-data', 'div.admin-comment-edit-form');
        event.preventDefault();
    });
    
    /*
     * Запрашивает данные в формате CSV
     */
    $('#admin-scv-comments-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'p.csv-success');
        event.preventDefault();
    });
});

$(function() {
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    Send.prototype = Object.create(AbstractSendForm.prototype);
    var send = new Send();
    
    /*
     * Запрашивает данные в формате CSV
     */
    $('#admin-scv-users-form').on('click', 'input:submit', function(event) {
        send.htmlSend(event, 'p.csv-success');
        event.preventDefault();
    });
});

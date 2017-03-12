$(function() {
    
    function SendMailingsForm() {
        AbstractSendForm.call(this);
    };
    
    SendMailingsForm.prototype = Object.create(AbstractSendForm.prototype);
    
    $('#mailings-form').on('click', 'input:submit', function(event) {
        (new SendMailingsForm()).htmlTimeoutSend(event, 'div.mailings-success');
        event.preventDefault();
    });
    
});

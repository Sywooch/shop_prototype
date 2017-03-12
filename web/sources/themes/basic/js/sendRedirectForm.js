$(function() {
    
    function SendRedirectForm() {
        AbstractSendForm.call(this);
    };
    
    SendRedirectForm.prototype = Object.create(AbstractSendForm.prototype);
    
    $('#login-form, #user-logout-form').on('click', 'input:submit', function(event) {
        (new SendRedirectForm()).redirectSend(event);
        event.preventDefault();
    });
    
});

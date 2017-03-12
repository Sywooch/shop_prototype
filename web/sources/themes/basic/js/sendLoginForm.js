$(function() {
    
    function SendLoginForm() {
        AbstractSendForm.call(this);
    };
    
    SendLoginForm.prototype = Object.create(AbstractSendForm.prototype);
    
    // Аутентифицирует пользователя
    $('#login-form').on('click', 'input:submit', function(event) {
        (new SendLoginForm()).redirectSend(event);
        event.preventDefault();
    });
    
});

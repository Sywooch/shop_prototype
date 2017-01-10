$(function() {
    
    function SendPasswordRecovery() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR) {
            if (typeof data == 'string') {
                $('div.recovery').html(data);
            } else if (typeof data == 'object') {
                for (var key in data) {
                    $('#' + key).closest('.form-group').find('.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#recovery-password-form').find('input[type="submit"]').click(function(event) {
        (new SendPasswordRecovery()).send(event);
        event.preventDefault();
    });
    
});

$(function() {
    
    function SendPasswordRecovery() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                $('div.recovery').html(data);
            } else if (typeof data == 'object') {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#recovery-password-form').find('input[type="submit"]').on('click', function(event) {
        (new SendPasswordRecovery()).send(event);
        event.preventDefault();
    });
    
});

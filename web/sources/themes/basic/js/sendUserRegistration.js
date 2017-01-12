$(function() {
    
    function SendUserRegistration() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                $('div.registration').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('.form-group').find('.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#registration-form').find('input[type="submit"]').on('click', function(event) {
        (new SendUserRegistration()).send(event);
        event.preventDefault();
    });
    
});

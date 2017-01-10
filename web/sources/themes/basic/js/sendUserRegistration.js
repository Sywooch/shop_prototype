$(function() {
    
    function SendUserRegistration() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR) {
            if (typeof data == 'string') {
                $('div.registration').html(data);
            } else if (typeof data == 'object') {
                for (var key in data) {
                    $('#' + key).closest('.form-group').find('.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#registration-form').find('input[type="submit"]').click(function(event) {
        (new SendUserRegistration()).send(event);
        event.preventDefault();
    });
    
});

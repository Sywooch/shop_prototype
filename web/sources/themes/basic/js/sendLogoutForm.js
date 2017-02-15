$(function() {
    
    function SendLogoutForm() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                window.location.replace(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#user-logout-form').on('click', 'input[type="submit"]', function(event) {
        (new SendLogoutForm()).send(event);
        event.preventDefault();
    });
    
});

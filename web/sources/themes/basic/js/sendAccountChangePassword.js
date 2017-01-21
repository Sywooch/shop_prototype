$(function() {
    
    function SendAccountChangePassword() {
        var self = this;
        self.infoDiv = $('div.account-change-password-success');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            self.form.find('input').blur();
            if (typeof data == 'string') {
                self.form.find('input[type="password"]').val('');
                self.infoDiv.html(data);
                setTimeout(timeoutRemove, 5000);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
        function timeoutRemove()
        {
            self.infoDiv.empty();
        }
    };
    
    $('#change-password-form').find('input[type="submit"]').on('click', function(event) {
        (new SendAccountChangePassword()).send(event);
        event.preventDefault();
    });
    
});

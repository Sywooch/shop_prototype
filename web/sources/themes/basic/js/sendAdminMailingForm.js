$(function() {
    
    function SendAdminMailingForm() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.closest('li').find('div.admin-mailing-previous-data').toggleClass('disable');
                self.form.closest('li').append(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.admin-mailings').on('click', 'form[id^="admin-mailing-get-form-"]', function(event) {
        (new SendAdminMailingForm()).send(event);
        event.preventDefault();
    });
    
});

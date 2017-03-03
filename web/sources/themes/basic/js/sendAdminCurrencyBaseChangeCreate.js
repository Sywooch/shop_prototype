$(function() {
    
    function SendAdminCurrencyBaseChangeCreate() {
        var self = this;
        self.infoDiv = $('div.admin-currency');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.find('input:text').val('');
                self.infoDiv.html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.admin-currency').on('change', 'input:checkbox', function(event) {
        (new SendAdminCurrencyBaseChangeCreate()).send(event);
        event.preventDefault();
    });
    
});

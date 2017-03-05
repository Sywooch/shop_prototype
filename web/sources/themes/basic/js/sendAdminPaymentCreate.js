$(function() {
    
    function SendAdminPaymentCreate() {
        var self = this;
        self.infoDiv = $('div.admin-payments');
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
    
    $('#payment-create-form').on('click', 'input:submit', function(event) {
        (new SendAdminPaymentCreate()).send(event);
        event.preventDefault();
    });
    
});

$(function() {
    
    function SendAdminPaymentChange() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.closest('li').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.admin-payments').on('click', 'form[id^="admin-payment-edit-form-"] > input:submit[name="send"]', function(event) {
        (new SendAdminPaymentChange()).send(event);
        event.preventDefault();
    });
    
    $('div.admin-payments').on('click', 'form[id^="admin-payment-edit-form-"] > input:submit[name="cancel"]', function(event) {
        var li = $(event.target).closest('li');
        li.find('div.admin-payment-previous-data').toggleClass('disable');
        li.find('div.admin-payment-edit-form').remove();
        event.preventDefault();
    });
    
});

$(function() {
    
    function SendAdminOrderDetailForm() {
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
    
    $('li[class^="admin-order-"]').on('click', 'input[type="submit"]', function(event) {
        (new SendAdminOrderDetailForm()).send(event);
        event.preventDefault();
    });
    
});

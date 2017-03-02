$(function() {
    
    function SendAdminCurrencyDelete() {
        var self = this;
        self.infoDiv = $('div.admin-currency');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.infoDiv.html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    self.form.find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.admin-currency').on('click', 'input:submit', function(event) {
        (new SendAdminCurrencyDelete()).send(event);
        event.preventDefault();
    });
    
});

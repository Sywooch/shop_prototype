$(function() {
    
    function SendMailings() {
        var self = this;
        self.infoDiv = $('div.mailings-success');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.find('input[type="text"]').val('');
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
    
    $('#mailings-form').find('input[type="submit"]').on('click', function(event) {
        (new SendMailings()).send(event);
        event.preventDefault();
    });
    
});

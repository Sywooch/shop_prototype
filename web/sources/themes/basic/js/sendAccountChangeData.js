$(function() {
    
    function SendAccountChangeData() {
        var self = this;
        self.infoDiv = $('div.account-change-data-success');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            self.form.find('input').blur();
            if (typeof data == 'string') {
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
    
    $('form').on('click', 'input:submit', function(event) {
        (new SendAccountChangeData()).send(event);
        event.preventDefault();
    });
    
});

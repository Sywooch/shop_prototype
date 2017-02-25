$(function() {
    
    function SendAdminUsersGetCsv() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                $('.csv-success').html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('.get-csv').on('click', '#admin-scv-users-form > input:submit', function(event) {
        (new SendAdminUsersGetCsv()).send(event);
        event.preventDefault();
    });
    
});

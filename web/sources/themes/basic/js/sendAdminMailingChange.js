$(function() {
    
    function SendAdminMailingChange() {
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
    
    $('div.admin-mailings').on('click', 'form[id^="admin-mailing-edit-form-"] > input:submit[name="send"]', function(event) {
        (new SendAdminMailingChange()).send(event);
        event.preventDefault();
    });
    
    $('div.admin-mailings').on('click', 'form[id^="admin-mailing-edit-form-"] > input:submit[name="cancel"]', function(event) {
        var li = $(event.target).closest('li');
        li.find('div.admin-mailing-previous-data').toggleClass('disable');
        li.find('div.admin-mailing-edit-form').remove();
        event.preventDefault();
    });
    
});

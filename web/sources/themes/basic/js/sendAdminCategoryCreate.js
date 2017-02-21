$(function() {
    
    function SendAdminCategoryCreate() {
        var self = this;
        self.infoDiv = $('div.product-categories');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.find('input[type="text"]').val('');
                self.form.find('input[type="checkbox"]').attr('checked', false)
                self.infoDiv.html(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('#category-create-form').on('click', 'input[type="submit"]', function(event) {
        (new SendAdminCategoryCreate()).send(event);
        event.preventDefault();
    });
    
});

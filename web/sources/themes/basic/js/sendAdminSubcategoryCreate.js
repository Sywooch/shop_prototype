$(function() {
    
    function SendAdminSubcategoryCreate() {
        var self = this;
        self.infoDiv = $('div.product-categories');
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
    
    $('#subcategory-create-form').on('click', ':submit', function(event) {
        (new SendAdminSubcategoryCreate()).send(event);
        event.preventDefault();
    });
    
});

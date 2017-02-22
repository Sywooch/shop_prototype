$(function() {
    
    function SendAdminCategoryCreate() {
        var self = this;
        self.infoDiv = $('div.product-categories');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'object' && data.length != 0) {
                if ('list' in data && 'options' in data) {
                    self.form.find('input:text').val('');
                    self.infoDiv.html(data.list);
                    $('#subcategoryform-id_category').html(data.options);
                } else {
                    for (var key in data) {
                        $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                    }
                }
            }
        };
    };
    
    $('#category-create-form').on('click', ':submit', function(event) {
        (new SendAdminCategoryCreate()).send(event);
        event.preventDefault();
    });
    
});

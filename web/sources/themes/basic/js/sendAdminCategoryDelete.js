$(function() {
    
    function SendAdminCategoryDelete() {
        var self = this;
        self.infoDiv = $('div.product-categories');
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'object' && data.length != 0) {
                if ('list' in data) {
                    self.form.find('input:text').val('');
                    self.infoDiv.html(data.list);
                    if ('options' in data) {
                        $('#subcategoryform-id_category').html(data.options);
                        $('select[data-disabled]').each(function(index, domElement) {
                            $(domElement).find('option:first').attr('disabled', true);
                        });
                    }
                } else {
                    for (var key in data) {
                        self.form.find('div.help-block').text(data[key]);
                    }
                }
            }
        };
    };
    
    $('div.product-categories').on('click', 'form[id^=admin-category-delete-form-], form[id^=admin-subcategory-delete-form-] > input:submit', function(event) {
        (new SendAdminCategoryDelete()).send(event);
        event.preventDefault();
    });
    
});

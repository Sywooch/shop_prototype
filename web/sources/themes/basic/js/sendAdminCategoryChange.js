$(function() {
    
    function SendAdminCategoryChange() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    self.form.find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('div.product-categories').on('change', 'input:checkbox', function(event) {
        (new SendAdminCategoryChange()).send(event);
        event.preventDefault();
    });
    
});

$(function() {
    
    function SendAdminCategoryDelete() {
        var self = this;
        self.infoDiv = $('div.product-categories');
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
    
    $('div.product-categories').on('click', 'input[type="submit"]', function(event) {
        (new SendAdminCategoryDelete()).send(event);
        event.preventDefault();
    });
    
});

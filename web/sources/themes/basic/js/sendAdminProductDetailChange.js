$(function() {
    
    function SendAdminProductDetailChange() {
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
    
    $('li').on('click', 'form[id^="admin-products-detail-send-form-"] > input[type="submit"][name="send"]', function(event) {
        (new SendAdminProductDetailChange()).send(event);
        event.preventDefault();
    });
    
    /*$('li').on('click', 'form[id^="admin-order-detail-send-form-"] > input[type="submit"][name="cancel"]', function(event) {
        var li = $(event.target).closest('li');
        li.find('div.admin-order-previous-data').toggleClass('disable');
        li.find('div.admin-order-change-form').remove();
        event.preventDefault();
    });*/
    
});

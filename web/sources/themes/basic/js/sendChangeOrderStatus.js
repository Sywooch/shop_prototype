$(function() {
    
    function SendChangeOrderStatus() {
        var self = this;
        self.send = function(event) {
            self.form = $(event.target).closest('form');
            self.form.submit();
        };
    };
    
    $('form[id^="order-status-form-"]').on('change', 'select', function(event) {
        (new SendChangeOrderStatus()).send(event);
        event.preventDefault();
    });
    
});

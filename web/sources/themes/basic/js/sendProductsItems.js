$(function() {
    
    function SendProductsItems() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'object' && data.length != 0) {
                //$('div.products-items').html(data['items']);
                //$('div.products-pagination').html(data['pagination']);
                $('div.products-filters').html(data['filters']);
            }
        };
    };
    
    $('div.products-filters').on('click', '#products-filters-form > input[type="submit"]', function(event) {
        (new SendProductsItems()).send(event);
        event.preventDefault();
    });
    
});

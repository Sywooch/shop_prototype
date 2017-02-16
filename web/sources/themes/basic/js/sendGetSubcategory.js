$(function() {
    
    function SendGetSubcategory() {
        var self = this;
        self.target;
        self.error = function(jqXHR, status, errorThrown)
        {
            alert(status + ' ' + jqXHR.responseText);
        };
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                var id = self.target.attr('id');
                if (id == 'adminproductsfiltersform-category') {
                    $('#adminproductsfiltersform-subcategory').html(data);
                } else if (id == 'adminproductform-id_category') {
                    $('#adminproductform-id_subcategory').html(data);
                }
            }
        };
        self.send = function(event) 
        {
            self.target = $(event.target);
            self.url = self.target.data('href');
            self.token = self.target.closest('form').find('input[name="_csrf"]').val();
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': self.url,
                'type': 'POST',
                'data': {category: self.target.val()},
                'dataType': 'json',
                'success': self.success,
                'error': self.error,
            });
        };
    };
    
    $('body').on('change', '#adminproductsfiltersform-category, #adminproductform-id_category', function(event) {
        (new SendGetSubcategory()).send(event);
        event.preventDefault();
    });
    
});

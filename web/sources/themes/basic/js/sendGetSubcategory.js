$(function() {
    
    function SendGetSubcategory() {
        var self = this;
        self.target;
        self.place = $('.calendar-place');
        self.error = function(jqXHR, status, errorThrown)
        {
            alert(status + ' ' + jqXHR.responseText);
        };
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                $('#adminproductsfiltersform-subcategory').html(data);
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
    
    $('#adminproductsfiltersform-category').on('change', function(event) {
        (new SendGetSubcategory()).send(event);
        event.preventDefault();
    });
    
});

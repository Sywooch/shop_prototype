jQuery(function() {
    
    function SendRequest() {
        var self = this;
        self._form;
        self._url;
        self._token;
        
        self.success = function(data, status, jqXHR) {
            var cart = $('#cart');
            if (cart.length > 0) {
                cart.remove();
            }
            $('#search-form').prepend(data);
            alert('Added!');
        };
        
        self.error = function(jqXHR, status, errorThrown) {
            alert(status + ' ' + jqXHR.responseText);
        };
        
        self.send = function(event) {
            self._form = $(event.target).closest('form');
            self._url = self._form.attr('action');
            self._token = self._form.find('input[name="_csrf"]').val();
            $.ajax({
                'headers': {'X-CSRF-Token': self._token},
                'url': self._url,
                'type': 'POST',
                'data': self._form.serialize(),
                'dataType': 'json',
                'success': self.success,
                'error': self.error,
            });
        };
    };
    
    $('#add-to-cart-form').find('input[type="submit"]').click(function(event) {
        (new SendRequest()).send(event);
        event.preventDefault();
    });
    
});

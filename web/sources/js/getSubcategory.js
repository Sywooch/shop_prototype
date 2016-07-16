jQuery(function() {
    
    function SendRequest() {
        this.form = $('form');
        this.token = $('meta[name="csrf-token"]').attr('content');
        function success(data, status, jqXHR) {
            $('#productsmodel-id_subcategory').empty();
            for (var i in data) {
                $('#productsmodel-id_subcategory').append('<option value="' + i + '">' + data[i] + '</option>');
            }
        };
        function error(jqXHR, status, errorThrown) {
            alert(jqXHR.responseText);
        };
        this.send = function(event) {
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': '/shop/web/get-subcategory-ajax',
                'type': 'POST',
                'data': {'categoriesId':$(event.target).val()},
                'dataType': 'json',
                'success': success,
                'error': error,
            });
        };
    };
    
    $('#productsmodel-id_categories').change(function(event) {
        (new SendRequest()).send(event);
    });
    
});

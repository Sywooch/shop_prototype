jQuery(function() {
    
    function SendRequest() {
        var self = this;
        this.form = $('form');
        this.token = $('meta[name="csrf-token"]').attr('content');
        function success(data, status, jqXHR) {
            $('.field-productsmodel-id_subcategory').removeClass('has-error');
            $('.field-productsmodel-id_subcategory .help-block').css('display', 'none');
            $('#productsmodel-id_subcategory').empty();
            for (var i in data) {
                var option = $('<option></option>').val(i).html(data[i]);
                $('#productsmodel-id_subcategory').append(option);
            }
        };
        function error(jqXHR, status, errorThrown) {
            alert(jqXHR.responseText);
        };
        this.send = function(event) {
            var value = $(event.target).val();
            if (!value) {
                alert('You must choose subcategory!');
            }
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': '/shop/web/get-subcategory-ajax',
                'type': 'POST',
                'data': {'categoriesId':value},
                'dataType': 'json',
                'success': success,
                'error': error,
            });
        };
    };
    
    $('#productsmodel-id_categories > option:first-child, #productsmodel-id_subcategory > option:first-child').attr('disabled', true);
    
    $('#productsmodel-id_categories').change(function(event) {
        (new SendRequest()).send(event);
    });
    
});

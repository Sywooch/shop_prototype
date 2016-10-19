jQuery(function() {
    
    function SendRequest() {
        SendRequestAbstract.apply(this, arguments);
    };
    
    $('select#productsmodel-id_category').change(function(event) {
        (new SendRequest()).send(event);
    });
    
});

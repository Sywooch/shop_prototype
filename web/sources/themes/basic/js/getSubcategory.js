jQuery(function() {
    
    function SendRequest() {
        SendRequestAbstract.apply(this, arguments);
    };
    
    $('select#productsmodel-id_categories').change(function(event) {
        (new SendRequest()).send(event);
    });
    
});

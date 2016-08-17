jQuery(function() {
    
    function DropDownDisable() {
        var self = this;
        self.run = function() {
            $('#productsmodel-id_categories').add('#productsmodel-id_subcategory').add('#brandsmodel-id').add('#subcategorymodel-id_categories').find('option:first-child').attr('disabled', true);
        };
    }
    
    (new DropDownDisable()).run();
    
});

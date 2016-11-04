jQuery(function() {
    
    function DropDownDisable() {
        var self = this;
        self.run = function() {
            $('#productsmodel-id_category').add('#productsmodel-id_subcategory').add('#productsmodel-id_brand').add('#subcategorymodel-id_category').add('#filtersmodel-category').find('option:first-child').attr('disabled', true);
        };
    }
    
    (new DropDownDisable()).run();
    
});

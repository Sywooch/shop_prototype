jQuery(function() {
    
    function DropDownDisable() {
        var self = this;
        self.run = function() {
            $('select#productsmodel-id_categories > option:first-child').add('select#productsmodel-id_subcategory > option:first-child').add('select#brandsmodel-id > option:first-child').add('select#subcategorymodel-id_categories > option:first-child').attr('disabled', true);
        };
    }
    
    (new DropDownDisable()).run();
    
});

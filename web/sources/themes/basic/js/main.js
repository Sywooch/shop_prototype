jQuery(function() {
    
    function SearchValidator() {
        var self = this;
        self.run = function() {
            $('#search-form').submit(self.handler);
        };
        self.handler = function(event) {
            if ($(event.currentTarget).find('input').val() == false) {
                event.preventDefault();
                event.stopPropagation();
            }
        };
    }
    
    (new SearchValidator()).run();
});

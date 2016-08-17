jQuery(function() {
    
    function CheckOneActive() {
        var self = this;
        self.run = function () {
            var checkedArray = $('#filtersmodel-getactive').add('#filtersmodel-getnotactive').find(':checked');
            if (!checkedArray.length) {
                $('#filtersmodel-getactive').add('#filtersmodel-getnotactive').attr('checked', true);
            }
        };
    }
    
    (new CheckOneActive()).run();
    
});

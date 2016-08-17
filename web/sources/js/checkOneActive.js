jQuery(function() {
    
    function CheckOneActive() {
        var self = this;
        self.run = function () {
            var checkedArray = $('#filtersmodel-getactive:checked').add('#filtersmodel-getnotactive:checked');
            if (!checkedArray.length) {
                $('#filtersmodel-getactive').add('#filtersmodel-getnotactive').attr('checked', true);
            }
        };
    }
    
    (new CheckOneActive()).run();
    
});

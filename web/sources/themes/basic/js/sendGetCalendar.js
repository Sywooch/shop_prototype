$(function() {
    
    function SendGetCalendar() {
        var self = this;
        self.target;
        self.place = $('.calendar-place');
        self.error = function(jqXHR, status, errorThrown)
        {
            alert(status + ' ' + jqXHR.responseText);
        };
        self.success = function(data, status, jqXHR)
        {
            if (typeof data == 'string') {
                $('.calendar-place').html(data);
                self.replace();
            }
        };
        self.replace = function()
        {
            self.place.on('click', 'td', function(event) {
                var eventTarget = $(event.target);
                self.place.empty();
                self.place.off('click');
                
                if (self.target.attr('class') == 'calendar-href-to') {
                    if ($('.calendar-href-from').attr('data-timestamp') > eventTarget.attr('data-timestamp')) {
                        return;
                    }
                }
                
                if (self.target.attr('class') == 'calendar-href-from') {
                    if ($('.calendar-href-to').attr('data-timestamp') < eventTarget.attr('data-timestamp')) {
                        return;
                    }
                }
                
                self.target.html(eventTarget.attr('data-format'));
                self.target.attr('data-timestamp', eventTarget.attr('data-timestamp'));
                event.preventDefault();
            });
        };
        self.send = function(event) 
        {
            self.target = $(event.target);
            self.url = self.target.attr('href');
            self.token = self.target.closest('form').find('input[name="_csrf"]').val();
            $.ajax({
                'headers': {'X-CSRF-Token': self.token},
                'url': self.url,
                'type': 'POST',
                'data': {timestamp: self.target.attr('data-timestamp')},
                'dataType': 'json',
                'success': self.success,
                'error': self.error,
            });
        };
    };
    
    $('.orders-filters').on('click', '[class^="calendar-href-"]', function(event) {
        (new SendGetCalendar()).send(event);
        event.preventDefault();
    });
    
});

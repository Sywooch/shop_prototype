function Calendar() {
    this.target;
    this.targetDate;
    this.url;
    this.token;
    
    this.send = function(event, container) {
        try {
            this.target = $(event.target);
            if (this.target.attr('class') == 'calendar-href-from' || this.target.attr('class') == 'calendar-href-to') {
                this.targetDate = this.target;
            }
            
            this.url = this.target.attr('href');
            this.token = this.target.closest('form').find('input[name="_csrf"]').val();
            
            $.ajax({
                'headers': {'X-CSRF-Token': this.token},
                'url': this.url,
                'type': 'POST',
                'data': {timestamp: this.target.attr('data-timestamp')},
                'dataType': 'json',
                'error': this.error,
                'success': success.bind(this),
            });
            
            function success(data, status, jqXHR) {
                if (typeof data == 'string') {
                    $(container).html(data);
                } else {
                    throw Error('Data is not string!');
                }
                
                $(container).on('click', 'td', this, function(event) {
                    var eventTarget = $(event.target);
                    $(container).empty();
                    $(container).off('click');
                    
                    if (event.data.targetDate.attr('class') == 'calendar-href-to') {
                        if ($('.calendar-href-from').attr('data-timestamp') > eventTarget.attr('data-timestamp')) {
                            return;
                        }
                        $('#ordersfiltersform-dateto').val(eventTarget.attr('data-timestamp'));
                    }
                    
                    if (event.data.targetDate.attr('class') == 'calendar-href-from') {
                        if ($('.calendar-href-to').attr('data-timestamp') < eventTarget.attr('data-timestamp')) {
                            return;
                        }
                        $('#ordersfiltersform-datefrom').val(eventTarget.attr('data-timestamp'));
                    }
                    
                    event.data.targetDate.html(eventTarget.attr('data-format'));
                    event.data.targetDate.attr('data-timestamp', eventTarget.attr('data-timestamp'));
                    event.preventDefault();
                });
            };
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
};

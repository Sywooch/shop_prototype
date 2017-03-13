$(function() {
    
    function Calendar() {
        this.target;
        this.url;
        this.token;
        
        this.send = function(event, container) {
            try {
                this.target = $(event.target);
                this.url = this.target.attr('href');
                this.token = this.target.closest('form').find('input[name="_csrf"]').val();
                
                $.ajax({
                    'headers': {'X-CSRF-Token': this.token},
                    'url': this.url,
                    'type': 'POST',
                    'data': {timestamp: this.target.attr('data-timestamp')},
                    'dataType': 'json',
                    'error': this.error,
                    'success': success,
                    'context':this.target
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
                        
                        if (event.data.attr('class') == 'calendar-href-to') {
                            if ($('.calendar-href-from').attr('data-timestamp') > eventTarget.attr('data-timestamp')) {
                                return;
                            }
                            $('#ordersfiltersform-dateto').val(eventTarget.attr('data-timestamp'));
                        }
                        
                        if (event.data.attr('class') == 'calendar-href-from') {
                            if ($('.calendar-href-to').attr('data-timestamp') < eventTarget.attr('data-timestamp')) {
                                return;
                            }
                            $('#ordersfiltersform-datefrom').val(eventTarget.attr('data-timestamp'));
                        }
                        
                        event.data.html(eventTarget.attr('data-format'));
                        event.data.attr('data-timestamp', eventTarget.attr('data-timestamp'));
                        event.preventDefault();
                    });
                };
            } catch (e) {
                console.log(e.name + ': ' + e.message);
            }
        };
    };
    
    Calendar.prototype = Object.create(AbstractSendForm.prototype);
    
    function Send()
    {
        AbstractSendForm.call(this);
    }
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /* 
     * Запрашивает календарь
    */
    $('div.orders-filters').on('click', '[class^="calendar-href"]', function(event) {
        (new Calendar()).send(event, 'p.calendar-place');
        event.preventDefault();
    });
    
    /*
     * Отправляет форму отменяющую заказ
     */
    $('li').on('click', 'form[id^="order-cancellation-form"] > input:submit', function(event) {
        (new Send()).htmlRemoveSend(event, 'span.account-order-status');
        event.preventDefault();
    });
    
});

$(function() {
    
    var send = new AbstractSendForm();
    
    /* 
     * Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('.shortCart').on('click', '#clean-cart-form > input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* 
     * Отправляет форму с данными для добавления товара в корзину, 
    * обновляет информацию и состоянии
    */
    $('#purchase-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с комментарием, 
    * обновляет информацию и состоянии
    */
    $('#comment-form').on('click', 'input[type="submit"]', function(event) {
        send.htmlSend(event, '.comment-success', true, false, true);
        event.preventDefault();
    });
    
    /*
     * Переносит форму покупки
     */
    function MoveToText()
    {
        Helpers.call(this);
        
        this.container;
        
        this.run = function() {
            try {
                this.container = $('.product-detail-right');
                
                var orderForm = $('.order-form-group');
                var commentsText = $('.comment-text');
                var commentsForm = $('.comment-form');
                var string = '';
                
                string += this.toString(orderForm);
                //string += this.toString(commentsText);
                //string += this.toString(commentsForm);
                
                this.container.append($(string));
            } catch (e) {
                console.log(e.name +': ' + e.message);
            }
        };
    };
    
    (new MoveToText()).run();
    
    /*
     * Управляет количеством единиц товара в покупке
     */
    /*function QuantityScale()
    {
        try {
            this.counter;
            this.container;
            this.count;
            
            this.run = function() {
                this.counter = $('#product-detail').find('.products-filters-quantity').find('.cifra');
                this.count = this.counter.text();
                this.container = $('#product-detail').find('.products-filters-quantity');
                
                this.container.on('click', '.minus', this, function(event) {
                    if (parseInt(event.data.count) > 1) {
                       event.data.counter.text(--event.data.count);
                       $('#purchaseform-quantity').val(event.data.count);
                    };
                });
                
                this.container.on('click', '.plus', this, function(event) {
                    event.data.counter.text(++event.data.count);
                    $('#purchaseform-quantity').val(event.data.count);
                });
            };
        } catch (e) {
            console.log(e.name +': ' + e.message);
        }
    };
    
    (new QuantityScale()).run();*/
});

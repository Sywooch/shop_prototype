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
     * Инициирует отправку формы logout
    */
    $('#user-info').on('click', '.logout', function(event) {
        $('#user-logout-form').submit();
    });
    
    /* 
     * Отправляет форму с данными для добавления товара в корзину, 
    * обновляет информацию и состоянии
    */
    $('#product-detail').on('click', '#purchase-form', function(event) {
        send.htmlSend(event, '#short-cart');
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
    
});

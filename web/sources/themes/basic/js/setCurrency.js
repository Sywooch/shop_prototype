function SetCurrency()
{
    AbstractSendForm.call(this);
    this.target;
    this.parent;
    this.id;
};

SetCurrency.prototype = Object.create(AbstractSendForm.prototype);

SetCurrency.prototype.baseSend =  function(event, success) {
    try {
        this.target = $(event.target);
        this.parent = this.target.closest('ul');
        this.id = this.target.closest('li').data('id');
        this.url = this.parent.data('action');
        this.token = this.parent.data('token');
        
        $.ajax({
            'headers': {'X-CSRF-Token':this.token},
            'url': this.url,
            'type': 'POST',
            'data': {id:this.id, url:this.parent.data('link')},
            'dataType': 'json',
            'error': this.error,
            'success': success.bind(this),
        });
    } catch (e) {
        console.log(e.name + ': ' + e.message);
    }
};


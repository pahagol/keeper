(function(keeper) {

keeper.widgetDialog = {
	// buttonIds: null,
	overlayElm: '.overlay',
	boxElm: '.message-box',
	messageElm: '#message',
	buttonElm: '.message-button',

	htmlNewButton: '<button id="button-dialog-%id%">%value%</button>',

	disposeByCenter: function(elm) {
		var left = parseInt(document.body.clientWidth / 2 - $(elm).outerWidth() / 2),
			top = parseInt(document.body.clientHeight / 2 - $(elm).outerHeight() / 2)
		;

		$(elm).css('left', left).css('top', top);
	},

	_closeEsc: function(e) {
		if (e.which == 27) this.hide();
	},

	show: function() {
		this.disposeByCenter(this.boxElm);
		$(this.overlayElm).show();
		$(this.boxElm).show();

		$('body').bind('keyup', keeper.bind(this._closeEsc, this));
	},

	hide: function() {
		$(this.overlayElm).hide();
		$(this.boxElm).hide();
		$(this.buttonElm).hide();
		$(this.buttonElm + ' button').unbind();
		$(this.buttonElm).html('');
		$('body').unbind();
	},

	setMessage: function(message, color) {
		$(this.messageElm).html(message);
		if (typeof color != 'undefined') {
			$(this.messageElm).css('color', color);
		}
	},

	addButton: function(value, callback) {
		var button = this.htmlNewButton.replace('%id%', value).replace('%value%', value),
			id = '#button-dialog-' + value;

		$(this.buttonElm).show();
		$(this.buttonElm).append(button);
		$(id).bind('click', callback);
	}
};

}(keeper));
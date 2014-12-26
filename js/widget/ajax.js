(function(keeper) {

keeper.widgetAjax = {

	_transport: null,
	_ajaxDoing: false,
	_ajaxTimeout: 1500,
	
	_getTransport: function() {
		if (!this._transport) {
			if (window.XMLHttpRequest) {
				try {
					this._transport = new XMLHttpRequest;
				} catch (e) {}
			} else if (window.ActiveXObject) {
				try {
					this._transport = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try {
						this._transport = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e) {}
				}
			}

			if (!this._transport) {
				throw new Error('Ajax initialization error');
			}
			
			this._transport.onreadystatechange = keeper.bind(function () {
				if (this._transport.readyState == 4) {
					this._onComplete.apply(this, arguments);
				}
			}, this);
		}

		return this._transport;
	},

	_onComplete: function() {
		if (!this._transport.status || this._transport.status >= 300) {
			keeper.showMessage('AJAX Transport status is wrong', 'red');
		} else {
			try {
				var response = JSON.parse(this._transport.responseText);
			} catch(e) {
				console.log(e);
				keeper.showMessage('Response is wrong format', 'red');
			}
			try {
				this.complete(response);
			} catch(e) {
				console.log(e);
				keeper.showMessage('Error in complete ', 'red');
			}
		}
	},

	send: function(address, params) {
		if (this._ajaxDoing) {
			return;
		}
		
		this._ajaxDoing = true;

		var t = this._getTransport(),
			post = keeper.toQueryString(params);
		
		t.open('POST', address, true);
		t.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		t.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		t.send(post);

		setTimeout(keeper.bind(function() {
			this._ajaxDoing = false;
		}, this), this._ajaxTimeout);
	},

	complete: function(response) {
		if (response.error) {
			keeper.showMessage(response.error, 'red');
		} else if (response.success) {
			this.success(response.success);
		} else if (response.login) {
			location.href = '/login';
		} else {
			keeper.showMessage('Что-то пошло не так', 'red');
		}
	},

	success: function(data) {}
};

}(keeper));
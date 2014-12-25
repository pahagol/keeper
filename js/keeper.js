(function(){

var keeper = {
	datePattern: '^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|1[0-9]|2[0-9]|3[01])$',
	pricePattern: '^\\d+$',

	ajaxDoing: false,
	_ajaxTimeout: 2000,
	_showMessageTimeout: 1000,

	summaMonth: 0,

	_pad: function(number) {
		return number.toString().replace(/^([0-9])$/, '0$1');
	},

	success: function() {},

	_defaultCallback: function(data) {
		try {
			var data = JSON.parse(data);
		} catch (e) {
			this.showMessage('Response is wrong format');
			return;
		}
		if (data.error) {
			this.showMessage(data.error);
		} else if (data.success) {
			this.success(data.success);
		} else if (data.login) {
			location.href = '/login';
		} else {
			this.showMessage('Что-то пошло не так');
		}
	},

	pressEnter: function(e, doing) {
		e = e || window.event;
		if (doing && (e.which == 13 || e.keyCode == 13)) {
			doing();
		}
	},

	bind: function(func, context) {
		if (Function.prototype.bind) {
			return Function.prototype.bind.call(func, context);
		}
		
		return function() {
			return func.apply(context, arguments);
		}
	},

	ajax: function(url, data, callback) {
		if (this.ajaxDoing) {
			return;
		}

		this.ajaxDoing = true;

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: (callback || this.bind(this._defaultCallback, this))
		});

		setTimeout(this.bind(function() {
			this.ajaxDoing = false;
		}, this), this._ajaxTimeout);
	},

	toQueryString: function(obj) {
		var str = [];

		if (typeof obj != 'object') {
			throw new Error('Object is wrong');
			return false;
		}

		for (var key in obj) {
			str.push(key + '=' + encodeURIComponent(obj[key]));
		}

		return str.join('&')
	},

	getLastDayOfMonth: function(year, month) {
		var date = new Date(year, month, 0);
		return date.getDate();
	},

	getParameterByName: function(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	},

	showMessage: function(message, color) {
		this.widgetDialog.setMessage(message, (color || 'red'));
		this.widgetDialog.show();

		setTimeout(this.bind(this.widgetDialog.hide, this.widgetDialog), this._showMessageTimeout);
	},

	activeMenu: function() {
		var link = location.pathname.replace('/', '');
		$('.sf-menu li').removeClass('current');
		$('#' + (!link ? 'add' : link)).addClass('current');
	},

	sumMonthPrice: function() {
		$('#price-month-sum').html(this.summaMonth);
	},

	logout: function() {
		document.cookie = "auth=; expires=Thu, 01-Jan-1970 00:00:01 GMT";
		document.location.href = '/login';
	}
};

window['keeper'] = keeper;

})();

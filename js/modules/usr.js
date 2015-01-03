(function(keeper) {

var moduleUsr = {
	elm: 'input[name="password"]',

	toggleInputPassword: function() {
		var type = $(this.elm).attr('type');
		$(this.elm).attr('type', (type == 'password' ? 'text' : 'password'));
	},
	save: function() {
		var password = $(this.elm).val();
		
		if (!password) {
			keeper.showMessage('Пароль не может быть пустым')
			return;
		}

		keeper.widgetAjax.success = function() {
			$(this.elm).val('');
			keeper.showMessage('Данные успешно обработаны', 'green');
			// location.href = '/usr';
		};
		keeper.widgetAjax.send('/usr/savePass', {
			password: password
		});
	}
};

window['moduleUsr'] = moduleUsr;

}(keeper));
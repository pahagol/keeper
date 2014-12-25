(function(keeper) {

var moduleUsr = {
	toggleInputPassword: function() {
		var type = $('input[name="password"]').attr('type');
		if (type == 'password') {
			$('input[name="password"]').attr('type', 'text');
		} else {
			$('input[name="password"]').attr('type', 'password');
		}
	},
	save: function() {
		var password = $('input[name="password"]').val();
		
		if (!password) {
			keeper.showMessage('Пароль не может быть пустым')
			return;
		}

		keeper.success = function() {
			keeper.showMessage('Данные успешно обработаны', 'green');
			location.href = '/usr';
		};
		keeper.ajax('/usr/savePass', {
			password: password
		});
	}
};

window['moduleUsr'] = moduleUsr;

}(keeper));
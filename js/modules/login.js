(function(keeper) {

var moduleLogin = {
	check: function() {
		var l = $('input[name="login"]').val(),
			p = $('input[name="password"]').val();
		
		if (!l) {
			keeper.showMessage('Логин пустой');
			return;
		}
		if (!p) {
			keeper.showMessage('Пароль пустой');
			return;
		}
		keeper.widgetAjax.success = function() {location.href = '/'};
		keeper.widgetAjax.send('/login/check', {login: l, password: p});
	}
};

window['moduleLogin'] = moduleLogin;

}(keeper));
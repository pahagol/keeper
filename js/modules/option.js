(function(keeper) {

var moduleOption = {
	
	_success: function() {
		keeper.showMessage('Данные успешно обработаны', 'green');
		location.href = '/option'
	},
	_checkOption: function(option) {
		var requiredOptions = ['dictionaryExpenseName', 'category', 'owner'];
	
		if ($.inArray(option, requiredOptions) == -1) {
			keeper.showMessage('Опция не верная');
			return false;
		}
		return true;
	},
	focusInput: function(input) {
		$(input).css('border', '2px solid #4285f4').css('margin', '0').css('height', '16px');
	},
	pressInput: function(input, id, option) {
		var e = window.event;
		if (e.which == 13 || e.keyCode == 13) {
			this.saveInput(input, id, option);
		}
	},
	saveInput: function(input, id, option) {
		var value = $(input).val(),
			current = $(input).parent().find('input[name="current"]').val();

		$(input).css('border', '0').css('margin', '0 2px 0 2px').css('height', '17px');

		if (!value || !current) {
			keeper.showMessage('Значение пустое');
			return;
		}

		if (value == current) {
			return;
		}

		keeper.widgetAjax.success = function() {
			$(input).blur(); 
			$(input).parent().find('input[name="current"]').val(value);
			keeper.showMessage('Данные успешно обработаны', 'green'); 
		};
		keeper.widgetAjax.send('/option/save', {id: id,	option: option,	value: value});
	},
	add: function(elm, option) {
		var value = $(elm).val();
		
		if (!this._checkOption(option) || !value) {
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// Ошибку выводить не надо, сделано для того чтоб
			// не было видимости бессмысленной операции, 
			// типа вставил символ, потом удалил, потом убрал курсор,
			// но при этом что то происходит
			//  
			// _showMessage('Опция пустая');
			return;
		}
		keeper.widgetAjax.success = this._success;
		keeper.widgetAjax.send('/option/add', {option: option, value: value});
	},
	delDialog: function(id, option) {
		var phrase = 'Удаляя опцию, также удаляются все данные закреплённые за опцией';

		if (!id || !this._checkOption(option)) {
			return;
		}
		this.id = id;
		this.option = option;

		keeper.widgetDialog.setMessage(phrase);
		keeper.widgetDialog.addButton('Ok', keeper.bind(this.del, this));
		keeper.widgetDialog.addButton('Cancel', keeper.bind(keeper.widgetDialog.hide, keeper.widgetDialog));
		keeper.widgetDialog.show();
	},
	del: function() {
		keeper.widgetDialog.hide();
		keeper.widgetAjax.success = keeper.bind(this.deleteNode, this);
		keeper.widgetAjax.send('/option/delete', {option: this.option, id: this.id});
	},
	deleteNode: function(data) {
		if (data.option && data.id) {
			$('#tr-' + data.option + '-' + data.id).remove();
		} else {
			this._success();
		}
	}
};

window['moduleOption'] = moduleOption;

}(keeper));
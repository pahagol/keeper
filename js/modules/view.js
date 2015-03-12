(function(keeper) {

var moduleView = {
	_currentValue: '',

	_success: function() {
		keeper.showMessage('Данные успешно обработаны', 'green');
		// $('input').blur();
	},

	_successExpenseName: function(input, value) {
		$(input).attr('data-value', value);
		$(input).blur();
		keeper.showMessage('Данные успешно обработаны', 'green');
	},

	_successPrice: function(data) {
		// отобразить в header сумму за месяц
		keeper.viewSumMonthPrice(data.summa);
		// удалить ноду
		if (data.id) {
			$('#tr-expense-' + data.id).remove();
		}
		// пересчитать сумму за день
		this.sumPrice(this.elmId);
		
		keeper.showMessage('Данные успешно обработаны', 'green');
	},
	
	changePeriod: function(elm) {
		var date = $(elm).find('option:selected').val();
		
		if (!date || !(new RegExp(keeper.datePattern)).test(date)) {
			keeper.showMessage('Дата не задана или формат не верный');
			return;
		}

		location.href = '/view?from=' + date;
	},

	enterAddDictionaryExpenseName: function(input, e) {
		if (e.which == 13) {
			var value = $(input).val();
			this.addDictionaryExpenseName(input, value);
		}
	},
	
	addDictionaryExpenseName: function(input, value, dictionaryExpenseNameId) {
		var currentValue = $(input).attr('data-value'),
			expenseId = $(input).attr('data-id'),
			value = value || '',
			dictionaryExpenseNameId = dictionaryExpenseNameId || 0;

		if (!expenseId) {
			keeper.showMessage('Id пустое');
			$(input).val(currentValue);
			return;
		}
		if (!value) {
			keeper.showMessage('Значение пустое');
			$(input).val(currentValue);
			return;
		}
		if (value == currentValue) {
			keeper.showMessage('Значение не изменено');
			return;
		}

		keeper.widgetAjax.success = keeper.bind(function() {this._successExpenseName(input, value)}, this);
		keeper.widgetAjax.send('/view/saveDictionaryExpenseName', {name: value, expenseId: expenseId, dictionaryExpenseNameId: dictionaryExpenseNameId});
			
	},
	pressPriceInput: function(input, elmId, e) {
		if (e.which != 13) {
			return;
		}	
		$(input).blur();
		this.savePriceInput(input, elmId)
	},
	savePriceInput: function(input, elmId) {
		var value = $(input).val(),
			expenseId = $(input).attr('data-id'),
			currentValue = $(input).attr('data-value');

		if (!expenseId) {
			keeper.showMessage('Id пустое');
			$(input).val(currentValue);
			return;
		}
		if (!value) {
			keeper.showMessage('Значение пустое');
			$(input).val(currentValue);
			return;
		}

		if (value == currentValue) {
			keeper.showMessage('Значение не изменено');
			return;
		}

		if (!(new RegExp(keeper.pricePattern)).test(value) || value <= 0) {
			keeper.showMessage('Расход не верный');
			$(input).val(currentValue);
			return;
		}

		this.elmId = elmId;
		
		keeper.widgetAjax.success = keeper.bind(this._successPrice, this);
		keeper.widgetAjax.send('/view/save', {
			id: expenseId,
			field: 'price',
			value: value
		});

		$(input).attr('data-value', value);
	},

	saveSelect: function(select, id, field) {
		var value = $(select).val();

		if (!value) {
			keeper.showMessage('Значение пустое');
			return;
		}

		keeper.widgetAjax.success = this._success;
		keeper.widgetAjax.send('/view/save', {
			id: id,
			field: field,
			value: value
		});
	},

	sumPrice: function(elmId) {
		var summa = 0,
			parent = $(elmId).parents('table');

		$(parent).find('[name="price"]').each(function() {
			summa += parseInt($(this).val());
		});

		if (summa) {
			$(elmId).html(summa);
		} else {
			$(parent).remove();
		}
	},

	del: function(id, elmId) {
		if (!id) {
			keeper.showMessage('Id is empty');
			return;
		}

		var from = keeper.getParameterByName('from');
		this.elmId = elmId;

		keeper.widgetAjax.success = keeper.bind(this._successPrice, this);
		keeper.widgetAjax.send('/view/delete', {id: id});
	},

	add: function(date) {
		if (!date || !(new RegExp(keeper.datePattern)).test(date)) {
			keeper.showMessage('Дата не задана или формат не верный');
			return;
		}

		location.href = '/?dateAdd=' + date;
	},

	toggle: function(elm) {
		$(elm).find('.block-data-hide').toggle();
	}
};

window['moduleView'] = moduleView;

}(keeper));
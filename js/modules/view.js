(function(keeper) {

var moduleView = {
	_currentValue: '',

	_success: function() {
		keeper.showMessage('Данные успешно обработаны', 'green');
		$('input').blur();
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

		keeper.success = this._success;
		keeper.ajax('/view/saveDictionaryExpenseName', {
			name: value,
			expenseId: expenseId,
			dictionaryExpenseNameId: dictionaryExpenseNameId
		});
			
	},
	savePriceInput: function(input, elmId, e) {
		var value = $(input).val(),
			expenseId = $(input).attr('data-id'),
			currentValue = $(input).attr('data-value')/*,
			span = $(input).parent().find('span')*/;

		if (e.which != 13) {
			return;
		}	

		// $(input).hide();
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
		
		// $(span).show().html(value);

		keeper.success = this._success;
		keeper.ajax('/view/save', {
			id: expenseId,
			field: 'price',
			value: value
		});

		if (elmId) {
			this.sumPrice(elmId);
		}
	},

	saveSelect: function(select, id, field) {
		var value = $(select).val();

		if (!value) {
			keeper.showMessage('Значение пустое');
			return;
		}

		keeper.success = this._success;
		keeper.ajax('/view/save', {
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

		keeper.summaMonth += summa;
		$(elmId).html(summa);
	},

	del: function(id, elmId) {
		if (!id) {
			keeper.showMessage('Id is empty');
			return;
		}

		var from = keeper.getParameterByName('from');

		keeper.widgetAjax.success = keeper.bind(function(data) {
			if (data.id && elmId) {
				$('#tr-expense-' + data.id).remove();
				this.sumPrice(elmId);
			}
			keeper.showMessage('Данные успешно обработаны', 'green');
			if (!data.id) {
				location.href = from ? '/view?from=' + from : '/view';
			}
		}, this);
		keeper.widgetAjax.send('/view/delete', {id: id});
	}
};

window['moduleView'] = moduleView;

}(keeper));
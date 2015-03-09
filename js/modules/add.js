(function(keeper) {

var moduleAdd = {
	clear: function(e) {
		if (e.which != 13) {
			$('#dictionaryExpenseNameId').val(0);
		}
	},
	save: function() {
		var error = '',
			categoryId = $('select[name="categoryId"]').val(),
			ownerId = $('select[name="ownerId"]').val(),
			dateAdd = $('input[name="dateAdd"]').val(),
			name = $('input[name="name"]').val(),
			price = $('input[name="price"]').val(),
			dictionaryExpenseNameId = $('#dictionaryExpenseNameId').val();
		
		if (!categoryId) {
			error += 'Категория не задана<br>';
		}
		if (!ownerId) {
			error += 'Собственник не задан<br>';
		}
		if (!(new RegExp(keeper.datePattern)).test(dateAdd)) {
			error += 'Дата не задана или формат не верный<br>';
		}
		if (!name) {
			error += 'Название не указано<br>';
		}
		if (!(new RegExp(keeper.pricePattern)).test(price) || parseInt(price) <= 0) {
			// $('input[name="price"]').val('');
			error += 'Расход не верный';
		}
		if (error) {
			keeper.showMessage(error)
			return;
		}

		keeper.widgetAjax.success = keeper.bind(this.success, this);
		keeper.widgetAjax.send('/add/save', {
			categoryId: categoryId,
			ownerId: ownerId,
			dateAdd: dateAdd,
			name: name,
			price: price,
			dictionaryExpenseNameId: dictionaryExpenseNameId
		});
	},
	success: function(data) {
		keeper.viewSumMonthPrice(data.price);
		keeper.showMessage('Данные успешно обработаны', 'green');
		$('input[name="name"]').focus();
	}
};

window['moduleAdd'] = moduleAdd;

}(keeper));
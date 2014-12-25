(function(){

var actionModule = (function() {
	var _datePattern = /[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])/,
		_pricePattern = /\d+/,
		_currentValue = '',
		_summaMonth = 0,

		// _pad = function(number) {
		// 	return number.toString().replace(/^([0-9])$/, '0$1');
		// },

		// _clearInputs = function() {
		// 	var now = new Date(),
		// 		formatNow = now.getFullYear() + '-' + _pad((now.getMonth() + 1)) + '-' + _pad(now.getDate());

		// 	$('select[name="categoryId"] option:first').prop('selected', true);
		// 	$('select[name="ownerId"] option:first').prop('selected', true);
		// 	$('input[name="dateAdd"]').val(formatNow);
		// 	$('input[name="name"]').val('');
		// 	$('input[name="price"]').val('')
		// },

		_success = function() {},

		_defaultCallback = function(data) {
			try {
				var data = JSON.parse(data);
			} catch (e) {
				_showMessage('Response is wrong format');
				return;
			}
			if (data.error) {
				_showMessage(data.error);
			} else if (data.success) {
				_success(data.success);
			} else if (data.login) {
				location.href = '/login';
			} else {
				_showMessage('Что-то пошло не так');
			}
		},

		_ajax = function(url, data, callback) {
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: (callback || _defaultCallback)
			});
		},

		_getLastDayOfMonth = function(year, month) {
			var date = new Date(year, month, 0);
			return date.getDate();
		},

		_getParameterByName = function(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
			return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		},

		_showMessage = function(message, color) {
			$('#message').html(message).css('color', color || 'red');
			$('.overlay').show();
			
			setTimeout(function() {
				$('#message').html('');
				$('.overlay').hide();
			}, 1500);
		};

	return {
		activeMenu: function() {
			var link = location.pathname.replace('/', '');
			$('.sf-menu li').removeClass('current');
			$('#' + (!link ? 'add' : link)).addClass('current');
		},

		sumMonthPrice: function() {
			$('#price-month-sum').html(_summaMonth);
		},

		add: {
			save: function() {
				var error = '',
					categoryId = $('select[name="categoryId"]').val(),
					ownerId = $('select[name="ownerId"]').val(),
					dateAdd = $('input[name="dateAdd"]').val(),
					name = $('input[name="name"]').val(),
					price = parseInt($('input[name="price"]').val());
				
				if (!categoryId) {
					error += 'Категория не задана<br>';
				}
				if (!ownerId) {
					error += 'Собственник не задан<br>';
				}
				if (!_datePattern.test(dateAdd)) {
					error += 'Дата не задана или формат не верный<br>';
				}
				if (!name) {
					error += 'Название не указано<br>';
				}
				if (!_pricePattern.test(price) || price <= 0) {
					$('input[name="price"]').val('');
					error += 'Расход не верный';
				}
				if (error) {
					_showMessage(error)
					return;
				}

				_success = function() {
					_showMessage('Данные успешно обработаны', 'green');
				};
				_ajax('/add/save', {
					categoryId: categoryId,
					ownerId: ownerId,
					dateAdd: dateAdd,
					name: name,
					price: price
				});
			}
		},

		view: {
			_success: function() {
				_showMessage('Данные успешно обработаны', 'green');
			},
			
			changePeriod: function(elm) {
				var date = $(elm).find('option:selected').val();
				
				if (!date || !_datePattern.test(date)) {
					_showMessage('Дата не задана или формат не верный');
					return;
				}

				location.href = '/view?from=' + date;
			},
			showInput: function(td) {
				_currentValue = $(td).find('input').val();
				$(td).find('input').show().focus();
				$(td).find('span').hide();
			},

			saveInput: function(input, id, field, elmId) {
				var value = $(input).val(),
					span = $(input).parent().find('span');

				$(input).hide();
				if (!value) {
					_showMessage('Значение пустое');
					$(span).show().html(_currentValue);
					return;
				}

				if (value == _currentValue) {
					$(span).show();
					_showMessage('Значение не изменено');
					return;
				}
				
				$(span).show().html(value);

				_success = this._success;
				_ajax('/view/save', {
					id: id,
					field: field,
					value: value
				});

				if (elmId) {
					this.sumPrice(elmId);
				}
			},

			saveSelect: function(select, id, field) {
				var value = $(select).val();

				if (!value) {
					_showMessage('Значение пустое');
					return;
				}

				_success = this._success;
				_ajax('/view/save', {
					id: id,
					field: field,
					value: value
				});
			},

			sumPrice: function(elmId) {
				var summa = 0,
					parent = $(elmId).parents('table');

				$(parent).find('.price').each(function() {
					summa += parseInt($(this).html());
				});

				_summaMonth += summa;
				$(elmId).html(summa);
			},

			del: function(id) {
				if (!id) {
					_showMessage('Id is empty');
					return;
				}

				var from = _getParameterByName('from');

				_success = function() {
					_showMessage('Данные успешно обработаны', 'green');
					location.href = from ? '/view?from=' + from : '/view';
				};
				_ajax('/view/delete', {id: id});
	 		}
 		},

		stat: {
			changeSelect: function() {
				var option = $('select[name="option"] option:selected').val(),
					date = $('select[name="date"] option:selected').val();
				
				if (!date || !_datePattern.test(date)) {
					_showMessage('Дата не задана или формат не верный');
					return;
				}

				var dates = date.split('-'),
					year = dates[0],
					month = dates[1],
					day = _getLastDayOfMonth(year, month),
					data = {
						option: option,
						from: date, 
						to: (year + '-' + month + '-' + day)
					};

				_success = function(data) {
					var categories = JSON.parse(data.categories),
						series = JSON.parse(data.series);
					
					actionModule.stat.showChart(categories, series);
				};

				_ajax('/stat/change', data);
			},
			showChart: function(categories, series) {
				var chart;

				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart-container'
					},
					title: {
						text: 'Статистика'
					},
					xAxis: {
						categories: categories
					},
					yAxis: {
						title: {
							text: 'Сумма'
						}
					},
					series: series
				});
			}
		},

		option: {
			_success: function() {
				_showMessage('Данные успешно обработаны', 'green');
				location.href = '/option'
			},
			_checkOption: function(option) {
				var requiredOptions = ['category', 'owner'];
			
				if ($.inArray(option, requiredOptions) == -1) {
					_showMessage('Опция не верная');
					return false;
				}
				return true;
			},
			save: function(elm, option) {
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
				_success = this._success;
				_ajax('/option/save', {option: option, value: value});
			},
			del: function(id, option) {
				var phrase = 'Удаляя опцию, также удаляются все данные этой опции';

				if (!id || !this._checkOption(option) || !confirm(phrase)) {
					return;
				}
				_success = this._success;
				_ajax('/option/delete', {option: option, id: id});
	 		}
		},

		login: {
			check: function() {
				var login = $('input[name="login"]').val(),
					password = $('input[name="password"]').val();
				
				if (!login) {
					_showMessage('Логин пустой');
					return;
				}
				if (!password) {
					_showMessage('Пароль пустой');
					return;
				}
				_success = function() {location.href = '/'};
				_ajax('/login/check', {login: login, password: password});
			}
		}
	};
}());

window['actionModule'] = actionModule;

})();

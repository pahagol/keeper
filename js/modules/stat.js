(function(keeper) {

var moduleStat = {
	_success: function(data) {
		var categories = JSON.parse(data.categories),
			series = JSON.parse(data.series),
			period = JSON.parse(data.period),
			title = 'c ' + period.from + ' по ' + period.to;
		
		this.showChart(categories, series, title);
	},
	
	changeSelect: function() {
		var option = $('select[name="option"] option:selected').val(),
			date = $('select[name="date"] option:selected').val();
		
		if (!date || !(new RegExp(keeper.datePattern)).test(date)) {
			keeper.showMessage('Дата не задана или формат не верный');
			return;
		}

		var dates = date.split('-'),
			year = dates[0],
			month = dates[1],
			day = keeper.getLastDayOfMonth(year, month),
			data = {
				option: option,
				from: date, 
				to: (year + '-' + month + '-' + day)
			};

		keeper.widgetAjax.success = keeper.bind(this._success, this);

		keeper.widgetAjax.send('/stat/change', data);
	},
	showChart: function(categories, series, title) {
		var chart,
			title = 'Статистика ' + (title || '');

		if (!categories || !series) {
			console.log(categories, series);
			keeper.showMessage('Error: categories or series are empty');
			return;
		}
// console.log(categories, series);
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart-container'
			},
			title: {
				text: title
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
};

window['moduleStat'] = moduleStat;

}(keeper));
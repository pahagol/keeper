(function(keeper) {

var moduleStat = {
	_success: function(data) {
		var categories = JSON.parse(data.categories),
			series = JSON.parse(data.series);
		
		this.showChart(categories, series);
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

		keeper.success = keeper.bind(this._success, this);

		keeper.ajax('/stat/change', data);
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
};

window['moduleStat'] = moduleStat;

}(keeper));
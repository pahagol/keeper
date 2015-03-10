<script src="/js/highcharts.js" type="text/javascript"></script>
<div style="width: 100%; height: 40px; background-color:grey">
	<select class="left" style="width:120px;margin:10px;background-color:#fff" onchange="moduleStat.changeSelect()" name="option">
		<? foreach ($options as $option): ?>
			<option value="<?=$option['value']?>"<?=$option['current'] ? ' selected' : ''?>><?=$option['html']?></option>
		<? endforeach ?>
	</select>
	<select class="right" style="width:100px;margin:10px;background-color:#fff" onchange="moduleStat.changeSelect()" name="date">
		<? foreach ($dates as $date): ?>
			<option value="<?=$date['value']?>"<?=$date['current'] ? ' selected' : ''?>><?=$date['html']?></option>
		<? endforeach ?>
	</select>
</div>
<div id="chart-container" style="width: 100%; height: 400px"></div>
<script type="text/javascript">
$(function() {	
	var categories = <?=$categories?>,
		series = <?=$series?>,
		period = <?=$period?>,
		title = 'c ' + period.from + ' по ' + period.to;
	moduleStat.showChart(categories, series, title);
}());
</script>

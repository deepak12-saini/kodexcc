	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/data.js"></script>
	<script src="https://code.highcharts.com/modules/drilldown.js"></script>
	
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>

	


	<div class="page-content">
		<div class="page-header">
			<h1>
				<?php echo ucfirst($napuser['NappUser']['name']).' '.ucfirst($napuser['NappUser']['lname']); ?> Attendance Report 			
				
				<?php echo $this->Form->create(null, ['url' => ['action' => 'attendanceReport', $napuser['NappUser']['id'] ?? null], 'style' => 'float:right;']); ?>
					<input type="text" name="start" value="<?php echo h($start); ?>" class="datepicker" placeholder="Start Date" >
					<input type="text" name="end" value="<?php echo h($end); ?>" class="datepicker_1" placeholder="End Date" >
					<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >
				<?php echo $this->Form->end(); ?>
			</h1>
			
			
		</div>
	
	<div class="row">
	
		
		
		<div class="col-xs-12">
			<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		</div>
		<div class="col-xs-12">
				<div id="container_new" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
		</div>
	</div>		
	
</div>		
<script>

Highcharts.chart('container_new', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Attendance Chart'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: '',
        colorByPoint: true,
        data: [{
            name: 'Present',
            y: <?php echo $present; ?>,
            sliced: true,
            selected: true
			}, {
            name: 'Absent',
            y: <?php echo $absent; ?>
			}, {
				name: 'Late',
				y: <?php echo $late; ?>
			}]
    }]
});


// Create the chart
Highcharts.chart('container', {
  chart: {
    type: 'column'
  },
  title: {
    text: 'Daily Attendance Report : <?php echo date('d M Y',strtotime($start)).' To '.date('d M Y',strtotime($end));?>'
  },
  subtitle: {
    text: ''
  },
  xAxis: {
    type: 'category'
  },
  yAxis: {
    title: {
      text: 'Time Frame'
    }

  },
  legend: {
    enabled: true
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '{point.y:2f} am'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
    pointFormat: '<span style="color:{point.color}">{point.name} </span>: <b>{point.y:2f}</b> am<br/>'
  },

  "series": [
    {
      "name": "Date",
      "colorByPoint": false,
      "data": <?php echo json_encode($datearr); ?> 
    }
  ]
});

jQuery(function(){ 
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});
	$('.datepicker_1').datepicker({
		format: 'yyyy-mm-dd',			
	});
});
</script>
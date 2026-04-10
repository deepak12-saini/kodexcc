	<div class="page-content">
		<div class="page-header">
			<h1>
			Sales Team Dashboard 
			
			<?php echo $this->Form->create(null, ['url' => ['action' => 'saledasboard'], 'style' => 'float:right;']); ?>
				<input type="text" name="startdate" value="<?php echo h($startdate); ?>" class="datepicker" placeholder="Start Date" >
				<input type="text" name="enddate" value="<?php echo h($enddate); ?>" class="datepicker1" placeholder="End Date" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >
				<a href="<?php echo SITEURL; ?>admin/sales/saledasboard" class="btn btn-warning btn-xs">Show All</a>
			<?php echo $this->Form->end(); ?>
			
			</h1>
			
		</div>
		

	 <div class="row">
	 
	 
	 <div class="col-lg-3 col-xs-6">
	  <!-- small box -->
	  <div class="small-box bg-yellow" style="background-color: #ebccd1 !important;color:#fff;">
		<div class="inner" style="padding:10px;">
		  <h3>#<?php  if(!empty($LogsArr)){ echo count($LogsArr); }else{  echo '0'; }  ?></h3>
		  <p>Dailer Called</p>
		</div>
			
	  </div>
	</div><!-- ./col -->
	<?php
	$userLog = array(); 
	foreach($LogsArr as $LogsArrs){
		$userLog[$LogsArrs['Log']['user_id']][] = $LogsArrs['Log']['id']; 
	}
	
	$totalsale = array();
	$saleperson = array();
	foreach ($SaleRepArr as $SaleRepArrs){
		if(!empty($SaleRepArrs['SaleQuestion']['id'])){
			$totalsale[$SaleRepArrs['SaleQuestion']['id']][] = $SaleRepArrs['SaleRep']['name'];
			$id = $SaleRepArrs['NappUser']['id'];
			//if($SaleRepArrs['SaleQuestion']['id'] == 1){
				$saleperson[$id][$SaleRepArrs['SaleQuestion']['id']][] = $SaleRepArrs['SaleRep']['id'];
			//}
		}		
	}	
	
	$final = array();
	$finalprospectmet = array();
	$finalprospectcall = array();
	foreach($napuser as  $napusers){
		$serier['name'] = $napusers['NappUser']['name'].' '.$napusers['NappUser']['lname'];
		$sermet['name'] = $napusers['NappUser']['name'].' '.$napusers['NappUser']['lname'];
		$sercall['name'] = $napusers['NappUser']['name'].' '.$napusers['NappUser']['lname'];
		if(isset($saleperson[$napusers['NappUser']['id']]) && !empty($saleperson[$napusers['NappUser']['id']])){
			ksort($saleperson[$napusers['NappUser']['id']]);
			$seriersval = array();
			
			$j=0;
			for($i=1; $i<=3; $i++){
				if(isset($saleperson[$napusers['NappUser']['id']][$i])){
					$seriersval[]  = count($saleperson[$napusers['NappUser']['id']][$i]);	
				}else{
					$seriersval[] = 0;
				}
			}
	
			
			if(isset($userLog[$napusers['NappUser']['id']])){
				$seriersval[] = count($userLog[$napusers['NappUser']['id']]);
			}
			
			$prospectmet  =  count($saleperson[$napusers['NappUser']['id']][1]) / count($totalsale[1]) * 100;
			$prospectcall  = count($saleperson[$napusers['NappUser']['id']][2]) / count($totalsale[2]) * 100;				
			
			$serier['data'] = 	$seriersval;			
			$sermet['y']= round($prospectmet);
			$sercall['y']= round($prospectcall);
						
		}else{
			$dilercall= 0;
			if(isset($userLog[$napusers['NappUser']['id']])){
				$dilercall = count($userLog[$napusers['NappUser']['id']]);
			}
			$sermet['y']=0;
			$sercall['y']= 0;
			$serier['data'] = 	array(0,0,0,$dilercall);
		}	
		$finalprospectmet[] = $sermet;
		$finalprospectcall[] = $sercall;
		$final[] = $serier;
	}

	$color = array('#F79263','#CC5D5E','#AEC95B','#09729f','#ebccd1','green');
	$i=0; 
	//$cate = 'Dailer Called , ';
	$cate = '';
	foreach($slatestype as $slatestypes){
	$cate .= "'".$slatestypes['SaleQuestion']['title']."' , ";
	?>	
		<div class="col-lg-3 col-xs-6">
		  <!-- small box -->
		  <div class="small-box bg-yellow" style="background-color: <?php echo $color[$i]; ?> !important;color:#fff;">
			<div class="inner" style="padding:10px;">
			  <h3>#<?php  if(!empty($totalsale[$slatestypes['SaleQuestion']['id']])){ echo count($totalsale[$slatestypes['SaleQuestion']['id']]); }else{  echo '0'; }  ?></h3>
			  <p><?php echo $slatestypes['SaleQuestion']['title']; ?></p>
			</div>
				
		  </div>
		</div><!-- ./col -->
	<?php 
	$i++; } 	
	$cate  =rtrim($cate,',');
	$cate = $cate."'Dailer Caller'";
	
	
	?>	
	
	</div><!-- /.row -->
	
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<br><br><br>

	<div class="row">
		<div class="col-lg-12">
			<div id="container" style="min-width: 310px; max-width: 100%; height: 400px; margin: 0 auto"></div>
		</div>
	</div><!-- /.row -->
	
	<div class="row">
		<div class="col-lg-6">
			<div id="piechart" style="min-width: 40px; height: 400px; max-width: 100%; margin: 0 auto"></div>
		</div>
		<div class="col-lg-6">
			<div id="piechart_called" style="min-width: 40px; height: 400px; max-width: 100%; margin: 0 auto"></div>
		</div>
	</div><!-- /.row -->
	<div class="row">	
		<div id="weekly" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	</div><!-- /.row -->
</div>		
<script>

Highcharts.chart('weekly', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Daily sale report'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: <?php echo json_encode($finaldate); ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Sale Report'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: <?php echo json_encode($finaldata); ?>
});
Highcharts.chart('piechart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Top Prospects Met (<?php echo date('d,M',strtotime($startdate)); ?> To <?php echo date('d,M',strtotime($enddate)); ?>)'
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
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'red'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: <?php echo json_encode($finalprospectmet); ?>
    }]
});


Highcharts.chart('piechart_called', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Top Prospects Called (<?php echo date('d,M',strtotime($startdate)); ?> To <?php echo date('d,M',strtotime($enddate)); ?>)'
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
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'red'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: <?php echo json_encode($finalprospectcall); ?>
    }]
});
Highcharts.chart('container', {
  chart: {
    type: 'bar'
  },
  title: {
    text: 'Sale Report'
  },
  xAxis: {
    categories: [<?php echo $cate; ?>]
  },
  yAxis: {
    min: 0,
    title: {
      text: 'Report:  <?php echo date('d, M Y',strtotime($startdate)); ?> To <?php echo date('d, M Y',strtotime($enddate)); ?>'
    }
  },
  legend: {
    reversed: true
  },
  plotOptions: {
    series: {
      stacking: 'normal'
    }
  },
  series: <?php echo json_encode($final);?>
});

jQuery(function(){ 
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});$('.datepicker1').datepicker({
		format: 'yyyy-mm-dd',			
	});
});


</script>
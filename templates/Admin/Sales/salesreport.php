	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	
	<div class="page-content">
		<div class="page-header">
			<h1>
				Duro Sales Report (<?php echo date('d M Y',strtotime($currentdate)); ?>)				
			</h1>
		</div>
	
	<?php 
	$i=1;
	foreach ($reportsummary as $reportsummarys): ?>
		
		<div class="row">
			<div class="col-xs-12">
				<h3><?php echo ucfirst($reportsummarys['name']); ?></h3>
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="simple-table" >
					<thead>
					<tr>
						<th>Type</th>				
						<th>Day</th>				
						<th>Week</th>				
						<th>Month</th>                            
					</tr>
					</thead>
					<tbody>
						
						<tr>
							<td>Face To Face</td>                  
							<td>
								<div id="container_f_d_<?php echo $i; ?>" style="min-width: 200px; height: 200px; max-width: 100px; margin: 0 auto"></div>

								<?php //echo $reportsummarys['currentday']['totalmeeting'].'/'.$reportsummarys['ff_day']; ?>
							</td>                  
							<td>
								<div id="container_f_w_<?php echo $i; ?>" style="min-width: 200px; height: 200px; max-width: 100px; margin: 0 auto"></div>

								<?php //echo $reportsummarys['currentweek']['totalmeeting'].'/'.$reportsummarys['ff_meeting']; ?>
							</td>                  
							<td>
								<div id="container_f_m_<?php echo $i; ?>" style="min-width: 200px; height: 200px; max-width: 100px; margin: 0 auto"></div>

								<?php //echo $reportsummarys['currentmonth']['totalmeeting'].'/'.$reportsummarys['ff_month']; ?>
							</td>                  
						</tr>
						<tr>
							<td>Called To</td>                  
							<td>
								<div id="container_c_d_<?php echo $i; ?>" style="min-width: 200px; height: 200px; max-width: 100px; margin: 0 auto"></div>

								<?php //echo $reportsummarys['currentday']['totalcalled'].'/'.$reportsummarys['cc_day']; ?>
							</td>                  
							<td>
								<div id="container_c_w_<?php echo $i; ?>" style="min-width: 200px; height: 200px; max-width: 100px; margin: 0 auto"></div>

								<?php //echo $reportsummarys['currentweek']['totalcalled'].'/'.$reportsummarys['cc_meeting']; ?>
							</td>                  
							<td>
								<div id="container_c_m_<?php echo $i; ?>" style="min-width: 200px; height: 200px; max-width: 100px; margin: 0 auto"></div>

								<?php //echo $reportsummarys['currentmonth']['totalcalled'].'/'.$reportsummarys['cc_month']; ?>
							</td>                  
						</tr>

					</tbody>
				</table>			
				</div>
			</div>
		</div>		
		
		<script>
		Highcharts.chart('container_f_d_<?php echo $i; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: ''
			},
			exporting: { enabled: false },
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
				name: 'F2F',
				colorByPoint: true,
				data: [{
					name: 'Achived',
					y: <?php echo $reportsummarys['currentday']['totalmeeting']?>,
					
				}, {
					name: 'Target',
					y: <?php echo $reportsummarys['ff_day']?>,
					sliced: true,
					selected: true
				}]
			}]
		});
		
		
		
		Highcharts.chart('container_f_w_<?php echo $i; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: ''
			},
			exporting: { enabled: false },
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
				name: 'F2F',
				colorByPoint: true,
				data: [{
					name: 'Achived',
					y: <?php echo $reportsummarys['currentweek']['totalmeeting']?>,
					
				}, {
					name: 'Target',
					y: <?php echo $reportsummarys['ff_meeting']?>,
					sliced: true,
					selected: true
				}]
			}]
		});
		Highcharts.chart('container_f_m_<?php echo $i; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
				
			},
			
			title: {
				text: ''
			},
			exporting: { enabled: false },
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
				name: 'F2F',
				colorByPoint: true,
				data: [{
					name: 'Achived',
					y: <?php echo $reportsummarys['currentmonth']['totalmeeting']?>,
					
				}, {
					name: 'Target',
					y: <?php echo $reportsummarys['ff_month']?>,
					sliced: true,
					selected: true
				}]
			}]
		});
		
		Highcharts.chart('container_c_d_<?php echo $i; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: ''
			},
			exporting: { enabled: false },
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
				name: 'Called To',
				colorByPoint: true,
				data: [{
					name: 'Achived',
					y: <?php echo $reportsummarys['currentday']['totalcalled']?>,
					
				}, {
					name: 'Target',
					y: <?php echo $reportsummarys['cc_day']?>,
					sliced: true,
					selected: true
				}]
			}]
		});
		
		
		
		Highcharts.chart('container_c_w_<?php echo $i; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: ''
			},
			exporting: { enabled: false },
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
				name: 'Called To',
				colorByPoint: true,
				data: [{
					name: 'Achived',
					y: <?php echo $reportsummarys['currentweek']['totalcalled']?>,
					
				}, {
					name: 'Target',
					y: <?php echo $reportsummarys['cc_meeting']?>,
					sliced: true,
					selected: true
				}]
			}]
		});
		Highcharts.chart('container_c_m_<?php echo $i; ?>', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
				
			},
			
			title: {
				text: ''
			},
			exporting: { enabled: false },
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
				name: 'Called To',
				colorByPoint: true,
				data: [{
					name: 'Achived',
					y: <?php echo $reportsummarys['currentmonth']['totalcalled']?>,
					
				}, {
					name: 'Target',
					y: <?php echo $reportsummarys['cc_month']?>,
					sliced: true,
					selected: true
				}]
			}]
		});
		</script>

	<?php $i++; endforeach; ?>
	
</div>		
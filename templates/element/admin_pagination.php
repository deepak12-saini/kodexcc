<style>
.admin-pager { margin-top: 4px; }
.admin-pager .dataTables_info { padding-top: 10px; color: #707070; }
.admin-pager .pagination { margin: 4px 0 0; float: right; }
.admin-pager .pagination > li > a,
.admin-pager .pagination > li > span { color: #438eb9; }
.admin-pager .pagination > .active > a,
.admin-pager .pagination > .active > a:hover,
.admin-pager .pagination > .active > span {
	background-color: #438eb9;
	border-color: #438eb9;
	color: #fff;
}
.admin-pager .pagination > .disabled > a { color: #bbb; }
</style>
<?php
$query = $this->request->getQueryParams();
unset($query['page'], $query['export']);
$this->Paginator->options(['url' => ['?' => $query]]);
?>
<div class="row admin-pager">
	<div class="col-sm-5">
		<div class="dataTables_info">
			<?php echo $this->Paginator->counter('Showing {{start}}–{{end}} of {{count}}'); ?>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="dataTables_paginate paging_simple_numbers">
			<ul class="pagination">
				<?php
				echo $this->Paginator->first('«');
				echo $this->Paginator->prev('‹');
				echo $this->Paginator->numbers(['modulus' => 5]);
				echo $this->Paginator->next('›');
				echo $this->Paginator->last('»');
				?>
			</ul>
		</div>
	</div>
</div>

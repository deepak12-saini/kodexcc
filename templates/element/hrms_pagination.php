<?php
$paging = $items ?? null;
if ($paging === null || !is_object($paging)) {
	return;
}
$query = $this->request->getQueryParams();
unset($query['page']);
$this->Paginator->setPaginated($paging);
$this->Paginator->options([
	'url' => ['?' => $query],
]);
?>
<div class="hrms-pager">
	<div class="hrms-pager-info">
		<?php echo $this->Paginator->counter('Showing {{start}}–{{end}} of {{count}}'); ?>
	</div>
	<div class="hrms-pager-links">
		<?php
		echo $this->Paginator->first('« First');
		echo $this->Paginator->prev('‹ Prev');
		echo $this->Paginator->numbers(['modulus' => 4]);
		echo $this->Paginator->next('Next ›');
		echo $this->Paginator->last('Last »');
		?>
	</div>
</div>

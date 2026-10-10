<?php
echo $this->Form->postLink('Delete', ['action' => 'delete', $id], [
    'confirm' => $confirm ?? 'Delete this record?',
    'class' => 'hrms-delete',
]);

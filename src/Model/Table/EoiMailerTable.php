<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Staff EOI email log rows (legacy `EoiMailer` model).
 */
class EoiMailerTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('eoi_mailer');
        $this->setPrimaryKey('id');
        $this->setDisplayField('projectname');

        $this->addBehavior('Timestamp');
    }
}

<?php
App::uses('AppModel', 'Model');
/**
 * Category Model
 *
 * @property Category $Category
 * @property  $
 */
class Category extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		
		
	);
public $actsAs = array(
    'Slugomatic.Slugomatic' => array(
        'fields' => 'category_name',
		'scope' => false,
        'conditions' => false,
        'slugfield' => 'slug',
        'separator' => '-',
        'overwrite' => true,
        'length' => 256,
        'lower' => true
    )
	);


}

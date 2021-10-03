<?php 
return [
	'products' => [
		[
		    'name'  => 'id',
		    'label' => 'ID',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'api_id',
		    'label' => 'API ID',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'title',
		    'label' => 'Title',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'slug',
		    'label' => 'Slug',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'sku',
		    'label' => 'SKU',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'content',
		    'label' => 'Content',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'thumbnail',
		    'label' => 'Thumbnail',
		    'type'  => 'text',
		    'is_relation' => false, 
		], [
		    'name'  => 'status',
		    'label' => 'Status',
		    'type'  => 'select',
		    'options' => [
		        'published' => 'Published',
		        'draft' => 'Draft',
		        'pending' => 'Pending',
		        'archive' => 'Archive',
		        'deleted' => 'Deleted',
		        'schedule' => 'Schedule',
		    ],
		    'is_relation' => false, 
		], [
		    'name'  => 'type',
		    'label' => 'Type',
		    'type'  => 'select',
		    'options' => [
		        'simple' => 'Simple',
		        'variation' => 'Variation',
		        // 'group' => 'Group',
		        // 'downloadble' => 'Downloadble',
		    ],
		    'is_relation' => false, 
		], [
		    'name'  => 'lang',
		    'label' => 'Language',
		    'type'  => 'language',
		    'is_relation' => false, 
		], [
		    'name'  => 'created_at',
		    'label' => 'Created Date',
		    'type'  => 'datetime_picker_range',
		    'is_relation' => false, 
		], [
		    'name'  => 'updated_at',
		    'label' => 'Updated Date',
		    'type'  => 'datetime_picker_range',
		    'is_relation' => false, 
		],
		// [
		//     'name'  => '',
		//     'label' => '',
		//     'type'  => '',
		//     'options' => [

		//     ],
		//     'is_relation' => false, 
		//     'relation_name' => ''
		// ]
	],

	'variations' => [

	],

	'users' => [

	],

	'orders' => [

	],

	'pages' => [

	],

	'resources' => [

	],

];
?>
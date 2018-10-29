<?php
$xpdo_meta_map['NotFoundParameter']= array (
  'package' => 'notfoundparamalert',
  'version' => '1.1',
  'table' => 'notfound_parameters',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'url_full' => '',
    'parameters_all' => '',
    'parameters_found' => '',
    'parameters_pattern' => '',
    'ip_address' => '',
    'time' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'url_full' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '2048',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'parameters_all' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '1820',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'parameters_found' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '1820',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'parameters_pattern' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'ip_address' => 
    array (
      'dbtype' => 'varbinary',
      'precision' => '16',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'time' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
      'index' => 'index',
    ),
  ),
);

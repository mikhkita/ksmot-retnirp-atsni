<?php
return array (
  'createUser' => 
  array (
    'type' => 0,
    'description' => 'создание пользователя',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'changeRole' => 
  array (
    'type' => 0,
    'description' => 'изменение роли пользователя',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'changeState' => 
  array (
    'type' => 0,
    'description' => 'изменение статуса пользователя',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'seeAdminPanel' => 
  array (
    'type' => 0,
    'description' => 'видеть панель администратора',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'manager' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'seeAdminPanel',
    ),
    'assignments' => 
    array (
      3 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      6 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'admin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'manager',
      1 => 'changeState',
      2 => 'createUser',
    ),
    'assignments' => 
    array (
      3 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      5 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      1 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'root' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'admin',
      1 => 'changeRole',
    ),
    'assignments' => 
    array (
      1 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
);

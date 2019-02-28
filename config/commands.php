<?php
// @file app/config/commands.php
return array(
    /**
    * An array of commands which are available to run from the admin console area.
    */
    'whitelist' => array(
        'migrate',
        'migrate:fresh',
        'migrate:install',
        'migrate:refresh',
        'migrate:reset',
        'migrate:rollback',
        'migrate:status',
        'make:model',
        'make:controller',
        'config:clear',
        'route:list',
        'env',
    ),

    /**
    * An array of commands which are self-created.
    */
    'custom' => array(
        'commands',
        'test',
        'log:clear',
        'log:view',
        'setbatch',
        'getbatch',
        'user:get',
        'user:create',
        'user:delete',
        'user:edit:active',
        'user:edit:verified',
        'user:edit:name',
        'user:edit:email',
        'user:edit:password',
        'user:projects:get',
        'user:projects:add',
        'user:projects:remove',
        'project:get',
    ),
);
?>
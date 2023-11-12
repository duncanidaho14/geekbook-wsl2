<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'project');

// Project repository
set('repository', 'git@github.com:duncanidaho14/geekbook-wsl2.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('gate.hidora.net')
    ->user('172891-7602')
    ->port('3022')
    ->set('deploy_path', '/var/deployer');

// Tasks
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'sf:vendors',
    'sf:clear_cache',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);



// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

task('sf:vendors', function () {
    run('cd {{release_path}} && composer install');
});

task('sf:clear_cache', function () {
    run('php {{release_path}}/bin/console cache:clear --env=prod');
});

// task('sf:migrate', function () {
//     run('php {{release_path}}/bin/console doctrine:migrations:migrate --env=prod');
// });

// Migrate database before symlink new release.

// before('deploy:symlink', 'database:migrate');


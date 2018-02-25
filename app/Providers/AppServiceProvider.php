<?php

namespace App\Providers;

use App\Components\File\Contracts\FileGroupRepository;
use App\Components\File\Contracts\FileRepository;
use App\Components\File\Repositories\MySQLFileGroupRepository;
use App\Components\File\Repositories\MySQLFileRepository;
use App\Components\User\Contracts\GroupRepository;
use App\Components\User\Contracts\PermissionRepository;
use App\Components\User\Contracts\UserRepository;

use App\Components\Subject\Contracts\SubjectRepository;
use App\Components\Subject\Repositories\MySQLSubjectRepository;

use App\Components\Subject\Contracts\UserSubjectRepository;
use App\Components\Subject\Repositories\MySQLUserSubjectRepository;

use App\Components\UserClass\Contracts\SubjectUsersRepository;
use App\Components\UserClass\Repositories\MySQLSubjectUsersRepository;

use App\Components\User\Repositories\MySQLGroupRepository;
use App\Components\User\Repositories\MySQLPermissionRepository;
use App\Components\User\Repositories\MySQLUserRepository;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        // bindings
        $this->app->bind(UserRepository::class, MySQLUserRepository::class);
        $this->app->bind(GroupRepository::class, MySQLGroupRepository::class);
        $this->app->bind(PermissionRepository::class, MySQLPermissionRepository::class);
        $this->app->bind(FileRepository::class, MySQLFileRepository::class);
        $this->app->bind(FileGroupRepository::class, MySQLFileGroupRepository::class);
        $this->app->bind(SubjectRepository::class, MySQLSubjectRepository::class);
        $this->app->bind(UserSubjectRepository::class, MySQLUserSubjectRepository::class);
        $this->app->bind(SubjectUsersRepository::class, MySQLSubjectUsersRepository::class);
    }
}

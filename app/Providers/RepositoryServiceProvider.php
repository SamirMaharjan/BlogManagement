<?php

namespace App\Providers;

use App\Interfaces\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        // Bind repository interfaces for the specified repositories
        foreach (self::REPO_BINDINGS as $dir => $repos) {
            if (is_array($repos)) {
                foreach ($repos as $repo) {
                    $this->bindRepository($dir, $repo);
                }
            } else {
                // If there is only one repository in the directory
                $this->bindRepository($dir, $repos);
            }
        }
    }

    /**
     * Bootstrap services.
     *
     */
    public function boot()
    {
        //
    }

    /**
     * dir => [repo, anotherRepo] or dir => repo
     * example of how to set bind repositories
     */
    const REPO_BINDINGS = [
        'Policies' => ['Policies'],
        'Plan' => ['Plan','ListPlan'],
        'V2\Plan' => ['Plan','ListPlan','AgentPlanHistory'],
        'V2\Member\Admin' => ['AdminMember'],
        'V2\Policy\Admin' => ['AdminPolicy'],
        'Member' => ['Member'],
        'Agent\Dashboard' => ['AgentDashboard'],
        'Carrier' => ['Carrier'],
        'Agent\Auth' => ['AgentAuth'],
        'User' => ['User', 'Profile'],
        'Post' => ['PostSetting', 'Album'],
        'Tag' => 'Tag',
        'Commission' => ['ListCommissionTypes','CommissionGeneration', 'PlanCommission', 'BillingCycle'],
        'Contract' => ['Contract','AddContract'],
        'User' => ['User'],
        'Blog' => ['Blog'],
        // 'AddContract' => ['AddContract'],
    ];

    /**
     * Bind repository interfaces for a given directory and repository name.
     *
     * @param string $dir
     * @param string $repo
     */
    private function bindRepository(string $dir,string $repo): void
    {
        $repoInterface = "App\\Interfaces\\{$dir}\\{$repo}RepositoryInterface";
        $repoImplementation = "App\\Repositories\\{$dir}\\{$repo}Repository";

        $this->app->bind($repoInterface, $repoImplementation);
    }
}

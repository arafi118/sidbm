<?php

namespace App\Console\Commands;

use App\Models\Kabupaten;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DeleteKabupatenSubdomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kabupaten:delete-subdomains {--suffix= : The domain suffix to search for (e.g. .sidbm.net)} {--search-column=web_kab_alternatif : The column to search in for the suffix} {--delete-column=web_kab : The column containing the domain to delete from cPanel and clear in DB} {--dry-run : Only show what would be done}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete subdomains for kabupaten based on a search column and a delete column';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $suffix = $this->option('suffix');
        $searchColumn = $this->option('search-column');
        $deleteColumn = $this->option('delete-column');
        $dryRun = $this->option('dry-run');

        if (empty($suffix)) {
            $this->error("Suffix is required. Use --suffix=.domain.com");
            return 1;
        }

        // Validate columns
        $validColumns = ['web_kab', 'web_kab_alternatif'];
        if (!in_array($searchColumn, $validColumns) || !in_array($deleteColumn, $validColumns)) {
            $this->error("Invalid column specified. Use web_kab or web_kab_alternatif.");
            return 1;
        }

        $kabupatens = Kabupaten::where($searchColumn, 'like', "%{$suffix}")->get();

        if ($kabupatens->isEmpty()) {
            $this->info("No kabupaten records found with suffix '{$suffix}' in column '{$searchColumn}'.");
            return 0;
        }

        $this->info("Found " . $kabupatens->count() . " records to process...");

        $processed = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($kabupatens as $kab) {
            $searchDomain = $kab->$searchColumn;
            $domainToDelete = $kab->$deleteColumn;

            if (empty($domainToDelete)) {
                $this->line("Processing: [Search: {$searchDomain}] -> [Delete: (EMPTY)] - Skipping");
                $skipped++;
                continue;
            }

            $this->info("Processing: [Search: {$searchDomain}] -> [Delete: {$domainToDelete}] (ID: {$kab->id})");

            if ($dryRun) {
                $this->line("  [DRY RUN] Would delete subdomain: {$domainToDelete} and clear DB column '{$deleteColumn}'");
                $processed++;
            } else {
                // Delete from cPanel
                $success = $this->deleteSubdomain($domainToDelete);
                if ($success) {
                    $this->info("  [CPANEL] Subdomain deleted.");
                    
                    // Clear DB record
                    $kab->$deleteColumn = null;
                    $kab->save();
                    $this->info("  [DB] Column '{$deleteColumn}' cleared.");
                    
                    $processed++;
                } else {
                    $this->error("  [CPANEL] Failed to delete subdomain.");
                    $failed++;
                }
            }
        }

        $this->info("--- Summary ---");
        $this->info("Processed: " . $processed);
        $this->info("Skipped: " . $skipped);
        $this->info("Failed: " . $failed);
        
        return 0;
    }

    /**
     * Delete subdomain via cPanel API 2
     */
    private function deleteSubdomain($fullDomain)
    {
        $rootDomain = env('CPANEL_DOMAIN', 'sidbm.net');
        $user = env('CPANEL_USER');
        $pass = env('CPANEL_PASS');
        $host = env('CPANEL_URL');
        $host = str_replace(['https://', 'http://'], '', $host);

        if (!$user || !$pass || !$host) {
            $this->error("  [ERROR] cPanel credentials not configured in .env");
            return false;
        }

        // Extract prefix
        $subdomain = explode('.', $fullDomain)[0];

        $params = [
            'cpanel_jsonapi_apiversion' => 2,
            'cpanel_jsonapi_module' => 'SubDomain',
            'cpanel_jsonapi_func' => 'delsubdomain',
            'domain' => "{$subdomain}.{$rootDomain}",
        ];

        $url = "https://{$host}:2083/json-api/cpanel?" . http_build_query($params);

        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Basic ' . base64_encode("{$user}:{$pass}"),
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $result = $data['cpanelresult'] ?? [];
                if (empty($result['error'])) {
                    return true;
                } else {
                    $this->error("  [API ERROR] " . $result['error']);
                    if (str_contains($result['error'], "does not exist") || str_contains($result['error'], "not found")) {
                        return true;
                    }
                    return false;
                }
            } else {
                $this->error("  [HTTP ERROR] Status: " . $response->status());
                return false;
            }
        } catch (\Exception $e) {
            $this->error("  [EXCEPTION] " . $e->getMessage());
            return false;
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateSubdomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kecamatan:generate-subdomains {--dry-run : Only show what would be done without making changes} {--recreate : Delete existing subdomain before creating to update path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate subdomains for kecamatan based on web_kec prefix and register them in cPanel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $recreate = $this->option('recreate');
        $kecamatans = Kecamatan::all();

        $this->info("Scanning " . $kecamatans->count() . " kecamatan records...");

        $processed = 0;
        $skipped = 0;
        $failed = 0;

        $rootDomain = env('CPANEL_DOMAIN', 'sidbm.net');

        foreach ($kecamatans as $kec) {
            $webKec = $kec->web_kec;

            // Skip if empty
            if (empty($webKec)) {
                $skipped++;
                continue;
            }

            // Extract prefix (e.g. "puspo" from "puspo.sidbm.net" or "puspo.sidbm.id")
            $prefix = explode('.', $webKec)[0];
            $newDomain = "{$prefix}.{$rootDomain}";
            
            $this->info("Processing: {$webKec} -> {$newDomain} (ID: {$kec->id})");

            if ($dryRun) {
                $this->line("  [DRY RUN] Would update DB and create subdomain: {$newDomain}");
                $processed++;
            } else {
                // Update Database
                $kec->web_kec = $newDomain;
                $kec->save();
                $this->info("  [DB] Updated to {$newDomain}");

                // Create/Update in cPanel
                $success = $this->createSubdomain($prefix, $recreate);
                if ($success) {
                    $this->info("  [CPANEL] Subdomain processed.");
                    $processed++;
                } else {
                    $this->error("  [CPANEL] Failed to process subdomain.");
                    $failed++;
                }
            }
        }

        $this->info("--- Summary ---");
        $this->info("Total Records: " . $kecamatans->count());
        $this->info("Processed: " . $processed);
        $this->info("Skipped: " . $skipped);
        $this->info("Failed: " . $failed);
    }

    /**
     * Call cPanel UAPI to create a subdomain
     */
    private function createSubdomain($subdomain, $recreate = false)
    {
        $rootDomain = env('CPANEL_DOMAIN', 'sidbm.net');
        $user = env('CPANEL_USER');
        $pass = env('CPANEL_PASS');
        $host = env('CPANEL_URL');
        $host = str_replace(['https://', 'http://'], '', $host);
        $dir = env('CPANEL_DIR', '/public_html');

        if (!$user || !$pass || !$host) {
            $this->error("  [ERROR] cPanel credentials (CPANEL_USER, CPANEL_PASS, CPANEL_URL) not configured in .env");
            return false;
        }

        if ($recreate) {
            $this->info("  [CPANEL] Deleting existing subdomain...");
            $this->deleteSubdomain($subdomain);
        }

        $query = [
            'domain' => $subdomain,
            'rootdomain' => $rootDomain,
            'canoff' => '1',
            'dir' => $dir,
            'disallowdot' => '1',
        ];

        // Construct URL based on cPanel UAPI standards
        // Example: https://hostname:2083/execute/SubDomain/addsubdomain
        $url = "https://{$host}:2083/execute/SubDomain/addsubdomain?" . http_build_query($query);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode("{$user}:{$pass}"),
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status']) && $data['status'] == 1) {
                    return true;
                } else {
                    $errors = isset($data['errors']) ? implode(', ', $data['errors']) : 'Unknown error';
                    $this->error("  [API ERROR] " . $errors);
                    return false;
                }
            } else {
                $this->error("  [HTTP ERROR] Status: " . $response->status() . " - " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            $this->error("  [EXCEPTION] " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete subdomain via cPanel UAPI
     */
    private function deleteSubdomain($subdomain)
    {
        $rootDomain = env('CPANEL_DOMAIN', 'sidbm.net');
        $user = env('CPANEL_USER');
        $pass = env('CPANEL_PASS');
        $host = env('CPANEL_URL');
        $host = str_replace(['https://', 'http://'], '', $host);

        $query = [
            'domain' => "{$subdomain}.{$rootDomain}",
        ];

        $url = "https://{$host}:2083/execute/SubDomain/delete_domain?" . http_build_query($query);

        try {
            Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode("{$user}:{$pass}"),
            ])->get($url);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

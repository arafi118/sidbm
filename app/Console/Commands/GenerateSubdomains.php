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
    protected $signature = 'kecamatan:generate-subdomains {--dry-run : Only show what would be done without making changes}';

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
        $kecamatans = Kecamatan::all();

        $this->info("Scanning " . $kecamatans->count() . " kecamatan records...");

        $processed = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($kecamatans as $kec) {
            $webKec = $kec->web_kec;

            // Skip if empty
            if (empty($webKec)) {
                $skipped++;
                continue;
            }

            // Skip if not sidbm.net
            if (!str_ends_with($webKec, 'sidbm.net')) {
                $skipped++;
                continue;
            }

            // Extract prefix (e.g. "puspo" from "puspo.sidbm.net")
            $prefix = explode('.', $webKec)[0];
            
            $this->info("Processing: {$prefix}.sidbm.net (ID: {$kec->id})");

            if ($dryRun) {
                $this->line("  [DRY RUN] Would create subdomain: {$prefix} on root domain: " . env('CPANEL_DOMAIN', 'sidbm.net'));
                $processed++;
            } else {
                $success = $this->createSubdomain($prefix);
                if ($success) {
                    $this->info("  [SUCCESS] Subdomain created.");
                    $processed++;
                } else {
                    $this->error("  [FAILED] Failed to create subdomain.");
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
    private function createSubdomain($subdomain)
    {
        $rootDomain = env('CPANEL_DOMAIN', 'sidbm.net');
        $user = env('CPANEL_USER');
        $pass = env('CPANEL_PASS');
        $host = env('CPANEL_HOST');
        $dir = env('CPANEL_DIR', '/public_html');

        if (!$user || !$pass || !$host) {
            $this->error("  [ERROR] cPanel credentials (CPANEL_USER, CPANEL_PASS, CPANEL_HOST) not configured in .env");
            return false;
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
}

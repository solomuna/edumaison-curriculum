<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/** Snapshot PostgreSQL local, atomique et vérifiable. */
final class DatabaseBackupCommand extends Command
{
    protected $signature = 'app:backup-database {--check : Vérifie la fraîcheur du dernier snapshot sans en créer}';
    protected $description = 'Crée ou contrôle un snapshot PostgreSQL dans le stockage persistant';

    public function handle(): int
    {
        $directory = storage_path('app/private/backups');
        File::ensureDirectoryExists($directory, 0700, true);

        if ($this->option('check')) {
            return $this->checkFreshness($directory);
        }

        $db = config('database.connections.'.config('database.default'));
        $prefix = Str::slug((string) config('app.name', 'application')) ?: 'application';
        $path = $directory.'/'.$prefix.'-'.now()->format('Ymd-His').'.dump';
        $temporaryPath = $path.'.tmp';

        $process = new Process([
            'pg_dump',
            '-h', (string) ($db['host'] ?? '127.0.0.1'),
            '-p', (string) ($db['port'] ?? '5432'),
            '-U', (string) ($db['username'] ?? 'postgres'),
            '-d', (string) ($db['database'] ?? 'postgres'),
            '--format=custom',
            '--no-owner',
            '--no-privileges',
            '--file='.$temporaryPath,
        ]);
        $process->setTimeout(3600);
        if (! empty($db['password'])) {
            $process->setEnv(['PGPASSWORD' => (string) $db['password']]);
        }

        $process->run();
        if (! $process->isSuccessful() || ! is_file($temporaryPath) || filesize($temporaryPath) === 0) {
            @unlink($temporaryPath);
            $this->error('Échec de pg_dump. Aucun snapshot valide créé.');
            return self::FAILURE;
        }

        rename($temporaryPath, $path);
        chmod($path, 0600);
        $this->prune($directory, (int) env('BACKUP_KEEP_DAYS', 7));

        $this->info(sprintf('Snapshot créé : %s (%.1f Ko)', basename($path), filesize($path) / 1024));
        return self::SUCCESS;
    }

    private function checkFreshness(string $directory): int
    {
        $files = glob($directory.'/*.dump') ?: [];
        usort($files, fn (string $a, string $b) => filemtime($b) <=> filemtime($a));
        $latest = $files[0] ?? null;
        $maxHours = max(1, (int) env('BACKUP_MAX_AGE_HOURS', 30));

        if (! $latest || filemtime($latest) < now()->subHours($maxHours)->getTimestamp()) {
            $this->error("Aucun snapshot PostgreSQL récent (maximum {$maxHours} h).");
            return self::FAILURE;
        }

        $this->info('Dernier snapshot sain : '.basename($latest));
        return self::SUCCESS;
    }

    private function prune(string $directory, int $keepDays): void
    {
        $threshold = now()->subDays(max(1, $keepDays))->getTimestamp();
        $files = glob($directory.'/*.dump') ?: [];
        usort($files, fn (string $a, string $b) => filemtime($b) <=> filemtime($a));

        foreach (array_slice($files, 1) as $file) {
            if (filemtime($file) < $threshold) {
                @unlink($file);
            }
        }
    }
}

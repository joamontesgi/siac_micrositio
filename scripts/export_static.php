<?php

use App\Support\SiacStaticSite;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\URL;
use Illuminate\Filesystem\Filesystem;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
config(['session.driver' => 'array', 'cache.default' => 'array']);
URL::forceRootUrl(SiacStaticSite::ORIGIN);
URL::forceScheme('https');

$files = new Filesystem;
$output = base_path('dist');
$files->ensureDirectoryExists($output);
$files->cleanDirectory($output);
// Publish only public assets, never PHP sources, environment files or storage.
foreach (['css', 'js', 'unal', 'images', 'fuentes-siac'] as $directory) {
    $files->copyDirectory(public_path($directory), $output.'/'.$directory);
}
foreach (['robots.txt', 'favicon.ico'] as $file) {
    $files->copy(public_path($file), $output.'/'.$file);
}
$site = new SiacStaticSite;
foreach ($site->pages() as $path => $html) {
    $files->ensureDirectoryExists(dirname($output.'/'.$path));
    $files->put($output.'/'.$path, $html);
}
$files->put($output.'/search-index.json', json_encode($site->searchIndex(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
fwrite(STDOUT, "Micrositio público exportado a dist/.\n");

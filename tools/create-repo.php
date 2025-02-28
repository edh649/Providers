<?php

require_once __DIR__.'/../vendor/autoload.php';

use Zttp\Zttp;

/**
 * Create a new repo with preset information.
 */
$repoName = $argv[1] ?? null;

if (empty($repoName)) {
    echo 'No name provided';
    exit(1);
}

$res = Zttp::withHeaders([
    'Accept'        => 'application/vnd.github.v3+json',
    'Authorization' => 'token '.getenv('GITHUB_TOKEN'),
])->post('https://api.github.com/orgs/SocialiteProviders/repos', [
    'name'        => $repoName,
    'description' => sprintf('[READ ONLY] Subtree split of the SocialiteProviders/%s Provider (see SocialiteProviders/Providers)', $repoName),
    'homepage'    => sprintf('https://socialiteproviders.com/%s/', $repoName),
    'has_issues'  => false,
]);

echo sprintf("Created Repo: %s, response: %s\n", ! $res->isOk() ? $res->body() : $res->json()['full_name'], $res->status());

if (! $res->isOk()) {
    exit(1);
}

$res = Zttp::withHeaders([
    'Accept'        => 'application/vnd.github.mercy-preview+json',
    'Authorization' => 'token '.getenv('GITHUB_TOKEN'),
])->put($res->json()['url'].'/topics', [
    'names' => ['laravel', 'oauth', 'socialite', 'oauth1', 'oauth2', 'socialite-providers', 'social-media'],
]);

echo sprintf("Updated Repo Topics: %s, response code: %s\n", $repoName, $res->status());

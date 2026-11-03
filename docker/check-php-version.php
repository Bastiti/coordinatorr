<?php
// Fails (exit 1) when the running PHP is end-of-life or older than the latest
// patch release of its branch, as published on php.net.

$installed = PHP_VERSION;
$branch = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
$allowOutdated = getenv('ALLOW_OUTDATED_PHP') === '1';

function fail(string $msg, bool $soft = false): void
{
    fwrite(STDERR, "PHP version check: $msg\n");
    if (!$soft) {
        exit(1);
    }
}

$ctx = stream_context_create(['http' => ['timeout' => 15, 'user_agent' => 'coordinatorr-build']]);

// latest patch release of this branch
$json = @file_get_contents("https://www.php.net/releases/index.php?json&version=$branch", false, $ctx);
$release = $json ? json_decode($json, true) : null;
if (!is_array($release) || empty($release['version'])) {
    fail("could not query php.net for the latest $branch release");
}
$latest = $release['version'];

// branch support status (end of security support date)
$json = @file_get_contents('https://www.php.net/releases/states.php', false, $ctx);
$states = $json ? json_decode($json, true) : null;
$eol = $states[PHP_MAJOR_VERSION][$branch]['security_support_end'] ?? null;
if ($eol === null) {
    fail("PHP $branch is not listed as supported on php.net (end of life?)");
}
if (strtotime($eol) < time()) {
    fail("PHP $branch reached end of security support on $eol");
}

echo "PHP version check: installed $installed, latest $branch is $latest, security support until $eol\n";

if (version_compare($installed, $latest, '<')) {
    fail("PHP $installed is outdated, $latest is available (pull a newer base image or set ALLOW_OUTDATED_PHP=1)", $allowOutdated);
}

echo "PHP version check: OK\n";

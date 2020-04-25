#!/usr/bin/env php
<?php

function ups($message = 'Something weng wrong', $exitCode = 1)
{
    fwrite(STDERR, $message);
    die($exitCode);
}

if (count($argv) < 4) {
    ups('We need more arguments for merge the json files.');
}

[ $script, $firstPath, $secondPath, $outputPath ] = $argv;

if (! (file_exists($firstPath) && file_exists($secondPath))) {
    ups('Unable to find the input files.');
}

function getJsonConent(string $path): array
{
    return (array) json_decode(file_get_contents($path), true);
}

$output = array_merge(getJsonConent($firstPath), getJsonConent($secondPath));

$fp = fopen($outputPath, 'w');
fwrite($fp, json_encode($output, JSON_PRETTY_PRINT));
fclose($fp);

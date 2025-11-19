<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class DetectMissingTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:missing {--lang=ar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect missing translation keys in lang/{lang}/*.php';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lang = $this->option('lang') ?? 'ar'; // Default language is Arabic

        if (!in_array($lang, ['ar', 'en'])) {
            $this->error("Invalid language: $lang. Use --lang=ar or --lang=en");
            return;
        }

        $this->info("Checking missing translations for language: $lang\n");
        $this->detectMissingTranslations($lang);
    }

    public function detectMissingTranslations($lang)
    {
        $langPath = resource_path("lang/{$lang}/"); // Dynamic language folder path
        $langFiles = [];

        // Load all language files dynamically
        foreach (glob($langPath . '*.php') as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $langFiles[$filename] = include($file);
        }

        // Get all PHP files in controllers and views
        $files = array_merge(
            glob(base_path('app/Http/Controllers/**/*.php')),
            glob(base_path('resources/views/**/*.php'))
        );

        $missingTranslations = [];
        $dynamicKeys = [];

        // Scan each file for `trans('file.key')` calls
        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Match `trans('group.key')` and `trans('group.' . $var)`
            preg_match_all('/\btrans\(["\']([a-zA-Z0-9_-]+)\.([^"\']+)["\']\)/m', $content, $staticMatches, PREG_OFFSET_CAPTURE);
            preg_match_all('/\btrans\(["\']([a-zA-Z0-9_-]+)\.["\']?\s*\.\s*(.*?)\)/m', $content, $dynamicMatches, PREG_OFFSET_CAPTURE);

            // Handle static translations (direct keys)
            foreach ($staticMatches[1] as $index => $match) {
                $group = $match[0]; // Translation group (file)
                $translationKey = $staticMatches[2][$index][0]; // Translation key
                $lineNumber = substr_count(substr($content, 0, $staticMatches[0][$index][1]), "\n") + 1; // Line number

                // Check if group exists
                if (!isset($langFiles[$group])) {
                    $missingTranslations[] = [
                        'file' => str_replace(base_path() . '/', '', $file),
                        'line' => $lineNumber,
                        'key' => "$group.$translationKey",
                        'error' => "❌ Missing Group: $group.php"
                    ];
                    continue;
                }

                // Check if key exists
                if (!array_key_exists($translationKey, $langFiles[$group])) {
                    $missingTranslations[] = [
                        'file' => str_replace(base_path() . '/', '', $file),
                        'line' => $lineNumber,
                        'key' => "$group.$translationKey",
                        'error' => "❌ Missing Key"
                    ];
                }
            }

            // Handle dynamic translations (keys using variables)
            foreach ($dynamicMatches[1] as $index => $match) {
                $group = $match[0]; // Translation group (file)
                $variable = trim($dynamicMatches[2][$index][0]); // Variable name
                $lineNumber = substr_count(substr($content, 0, $dynamicMatches[0][$index][1]), "\n") + 1; // Line number

                $dynamicKeys[] = [
                    'file' => str_replace(base_path() . '/', '', $file),
                    'line' => $lineNumber,
                    'key' => "$group.{{$variable}}",
                    'error' => "⚠️ Dynamic Key (Cannot Validate Automatically)"
                ];
            }
        }

        // Output missing translations
        if (!empty($missingTranslations)) {
            $this->info("❌ Missing Translations:");
            foreach ($missingTranslations as $missing) {
                $this->info("- {$missing['key']} (File: {$missing['file']} | Line: {$missing['line']})");
            }
        }

        // Warn about dynamic keys
        if (!empty($dynamicKeys)) {
            $this->warn("\n⚠️ Dynamic Translations Detected (Manual Check Required):");
            foreach ($dynamicKeys as $dynamic) {
                $this->warn("- {$dynamic['key']} (File: {$dynamic['file']} | Line: {$dynamic['line']})");
            }
        }

        if (empty($missingTranslations) && empty($dynamicKeys)) {
            $this->info("\n✅ No missing translations found.");
        }
    }

    
    // public function detectMissingTranslationsNoDynamicsTrans($lang)
    // {
    //     $langPath = resource_path("lang/{$lang}/"); // Dynamic language folder path
    //     $langFiles = [];

    //     // Load all language files dynamically
    //     foreach (glob($langPath . '*.php') as $file) {
    //         $filename = pathinfo($file, PATHINFO_FILENAME);
    //         $langFiles[$filename] = include($file);
    //     }

    //     // Get all PHP files in controllers and views
    //     $files = array_merge(
    //         glob(base_path('app/Http/Controllers/**/*.php')),
    //         glob(base_path('resources/views/**/*.php'))
    //     );

    //     $missingTranslations = [];

    //     // Scan each file for `trans('file.key')` calls
    //     foreach ($files as $file) {
    //         $content = file_get_contents($file);

    //         // Ignore commented-out `trans()` calls
    //         preg_match_all('/^[^\/\/]*\btrans\(["\']([a-zA-Z0-9_-]+)\.([^"\']+)["\']\)/m', $content, $matches, PREG_OFFSET_CAPTURE);

    //         foreach ($matches[1] as $index => $match) {
    //             $group = $match[0]; // Translation file name (e.g., 'translation', 'validation')
    //             $translationKey = $matches[2][$index][0]; // Translation key (e.g., 'have-no-action')
    //             $lineNumber = substr_count(substr($content, 0, $matches[0][$index][1]), "\n") + 1; // Calculate line number

    //             // Check if the group (file) exists
    //             if (!isset($langFiles[$group])) {
    //                 $missingTranslations[] = [
    //                     'file' => $file,
    //                     'line' => $lineNumber,
    //                     'key' => "$group.$translationKey",
    //                     'error' => "Missing Group: $group.php"
    //                 ];
    //                 continue;
    //             }

    //             // Check if the key exists in the group file
    //             if (!array_key_exists($translationKey, $langFiles[$group])) {
    //                 // Convert absolute path to relative path from Laravel root
    //                 $relativePath = str_replace(base_path() . '/', '', $file);

    //                 $missingTranslations[] = [
    //                     'file' => $relativePath,
    //                     'line' => $lineNumber,
    //                     'key' => "$group.$translationKey",
    //                     'error' => "Missing Key"
    //                 ];
    //             }
    //         }
    //     }

    //     // Output the missing translation keys
    //     if (!empty($missingTranslations)) {
    //         $this->info("❌ Missing Translations:");
    //         foreach ($missingTranslations as $missing) {
    //             $this->info("- {$missing['key']} \n(File: {$missing['file']} | Line: {$missing['line']}) \n\n");
    //         }
    //     } else {
    //         $this->info("✅ No missing translations found.");
    //     }
    // }

}

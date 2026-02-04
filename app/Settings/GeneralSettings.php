<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    // public string $site_name;
    // public ?string $site_logo;
    // public string $navigation_type;
    // public bool $spa_mode;
    // public string $content_width;

    public static function group(): string
    {
        return 'general';
    }
}

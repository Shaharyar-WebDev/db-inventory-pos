<?php

use Filament\Support\Enums\Width;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'My App');
        $this->migrator->add('general.site_logo', null);

        $this->migrator->add('general.background_type', 'solid');

        $this->migrator->add('general.content_width', Width::SevenExtraLarge);
        $this->migrator->add('general.navigation_type', 'sidebar');
        $this->migrator->add('general.spa_mode', false);

        $this->migrator->add('general.address', 'Karachi');
        $this->migrator->add('general.contact', '03154573767');
    }
};

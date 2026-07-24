<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_ceo_quote.quote', null);
        $this->migrator->add('our_story_ceo_quote.ceo_name', null);
        $this->migrator->add('our_story_ceo_quote.ceo_designation', null);
        $this->migrator->add('our_story_ceo_quote.ceo_image_url', null);
    }
};

<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('accreditation_cinematic.heading', 'Uncompromising Quality, Global Excellence');
        $this->migrator->add('accreditation_cinematic.text', 'Every partnership we forge and every accreditation we hold is a testament to our unwavering commitment to providing world-class business education.');
        $this->migrator->add('accreditation_cinematic.image_url', 'https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920');
        $this->migrator->add('accreditation_cinematic.image_url_asset_id', null);
    }
};

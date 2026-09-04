<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('dual_mba_hero.credentials_enabled', true);
        $this->migrator->add('dual_mba_hero.credentials_label', 'Your Dual Qualification');
        $this->migrator->add('dual_mba_hero.credentials', [
            ['iso2' => 'gb', 'title' => "UK Master's", 'subtitle' => null],
            ['iso2' => 'ch', 'title' => 'Swiss MBA', 'subtitle' => null],
        ]);

        $this->migrator->update(
            'dual_mba_hero.sub',
            fn () => '<p>One Programme. Two International Qualifications. Earn a UK Master\'s and a Swiss MBA. Unlimited Career Opportunities.</p>'
        );

        $this->migrator->update('dual_mba_overview.cards', function ($cards): array {
            return array_map(function ($card): array {
                $card = is_array($card) ? $card : (array) $card;

                if (($card['title'] ?? '') === 'Triple Qualification') {
                    $card['title'] = 'UK & Swiss Dual Qualification';
                    $card['text'] = 'Earn a UK Master\'s and a Swiss MBA through one integrated programme.';
                }

                return $card;
            }, is_array($cards) ? $cards : []);
        });

        $this->migrator->update('dual_mba_faq.items', function ($items): array {
            return array_map(function ($item): array {
                $item = is_array($item) ? $item : (array) $item;

                if (str_contains($item['question'] ?? '', 'internationally recognised')) {
                    $item['answer'] = '<p>Yes. The Dual MBA Programme awards a UK Master\'s and a Swiss MBA — two internationally recognised qualifications earned through one integrated learning pathway.</p>';
                }

                return $item;
            }, is_array($items) ? $items : []);
        });
    }
};

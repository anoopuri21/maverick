<?php

namespace App\Filament\Pages;

use App\Settings\ProgramsDetailChromeSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageProgramsDetailChrome extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Programs';
    protected static ?string $navigationLabel = 'Program Detail Labels';
    protected static ?int $navigationSort = 10;
    protected static string $settings = ProgramsDetailChromeSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Action Buttons')->schema([
                TextInput::make('enquire_label')->label('Enquire Button Label'),
                TextInput::make('apply_label')->label('Apply Button Label'),
                TextInput::make('scholarship_badge')->label('Scholarship Badge Text'),
                TextInput::make('download_brochure_label')->label('Download Brochure Label'),
            ]),

            Section::make('Highlights & Glance')->schema([
                TextInput::make('quick_highlights_label')->label('Quick Highlights Label'),
                TextInput::make('glance_heading')->label('At a Glance Heading'),
            ]),

            Section::make('Main Content Sections')->schema([
                TextInput::make('overview_label')->label('Overview Section Label'),
                TextInput::make('overview_heading')->label('Overview Heading'),
                TextInput::make('why_label')->label('Why Section Label'),
                TextInput::make('why_heading')->label('Why Section Heading'),
            ]),

            Section::make('Learning & Career')->schema([
                TextInput::make('learn_label')->label('Learn Section Label'),
                TextInput::make('learn_heading')->label('Learn Section Heading'),
                RichEditor::make('learn_intro')->label('Learn Section Intro'),
                TextInput::make('career_label')->label('Career Section Label'),
                TextInput::make('career_heading')->label('Career Section Heading'),
                RichEditor::make('career_intro')->label('Career Section Intro'),
            ]),

            Section::make('Structure & University')->schema([
                TextInput::make('structure_label')->label('Structure Section Label'),
                TextInput::make('structure_heading')->label('Structure Section Heading'),
                RichEditor::make('structure_intro')->label('Structure Section Intro'),
                TextInput::make('university_label')->label('University Section Label'),
                TextInput::make('university_heading')->label('University Section Heading'),
            ]),

            Section::make('Accreditation & Partners')->schema([
                TextInput::make('accreditation_label')->label('Accreditation Section Label'),
                TextInput::make('accreditation_heading')->label('Accreditation Section Heading'),
                TextInput::make('partner_label')->label('Partner Section Label'),
                TextInput::make('partner_heading')->label('Partner Section Heading'),
                RichEditor::make('partner_intro')->label('Partner Section Intro'),
            ]),

            Section::make('Stories & Fees')->schema([
                TextInput::make('stories_label')->label('Stories Section Label'),
                TextInput::make('stories_heading')->label('Stories Section Heading'),
                RichEditor::make('fees_intro')->label('Fees Section Intro'),
                TextInput::make('fees_request_label')->label('Fee Request Button Label'),
            ]),

            Section::make('FAQ & Enquiry')->schema([
                TextInput::make('faq_heading')->label('FAQ Section Heading'),
                TextInput::make('enquiry_heading')->label('Enquiry Section Heading'),
                Textarea::make('enquiry_subheading')->rows(3)->label('Enquiry Section Subheading'),
            ]),

            Section::make('Final CTA')->schema([
                TextInput::make('final_cta_heading')->label('Final CTA Heading'),
                RichEditor::make('final_cta_body')->label('Final CTA Body Text'),
            ]),
        ]);
    }
}

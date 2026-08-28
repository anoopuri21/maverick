<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // The attached docs/listing.pdf is the only programme and university
        // source for this landing-page catalogue.
        $mbaTabs = json_decode(<<<'MBA_JSON'
[
            {
                        "key": "rbs-mba",
                        "label": "Rushford Business School — MBA",
                        "universities": [
                                    {
                                                "name": "Rushford Business School (RBS), Switzerland",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/specialized-mba.jpg",
                                                "image_asset_id": null,
                                                "specification": {
                                                            "category": "Postgraduate Courses - MBA",
                                                            "qualification": "MBA",
                                                            "listing_page": "PDF page 1",
                                                            "programme_count": "12"
                                                },
                                                "programs": [
                                                            {
                                                                        "title": "MBA in Sustainability, Energy and Environment"
                                                            },
                                                            {
                                                                        "title": "MBA in Strategic Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Real Estate Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Human Resource Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Marketing"
                                                            },
                                                            {
                                                                        "title": "MBA in Logistics & Supply Chain Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Healthcare Leadership"
                                                            },
                                                            {
                                                                        "title": "MBA in Hospitality & Tourism Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Health Economics"
                                                            },
                                                            {
                                                                        "title": "MBA in Entrepreneurship and Innovation"
                                                            },
                                                            {
                                                                        "title": "MBA in Finance"
                                                            },
                                                            {
                                                                        "title": "Master of Business Administration (MBA)"
                                                            }
                                                ]
                                    }
                        ]
            },
            {
                        "key": "gau-mba",
                        "label": "Girne American University — MBA",
                        "universities": [
                                    {
                                                "name": "Girne American University (GAU), North Cyprus",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/business-management-mba.jpg",
                                                "image_asset_id": null,
                                                "specification": {
                                                            "category": "MBA Programs",
                                                            "qualification": "MBA",
                                                            "listing_page": "PDF page 2",
                                                            "programme_count": "5"
                                                },
                                                "programs": [
                                                            {
                                                                        "title": "MBA in Business Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Financial Management"
                                                            },
                                                            {
                                                                        "title": "MBA in International Business Management"
                                                            },
                                                            {
                                                                        "title": "MBA in Management Information Systems"
                                                            },
                                                            {
                                                                        "title": "MBA in Marketing"
                                                            }
                                                ]
                                    }
                        ]
            },
            {
                        "key": "gau-emba",
                        "label": "Girne American University — Executive MBA",
                        "universities": [
                                    {
                                                "name": "Girne American University (GAU), North Cyprus",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/executive-mba.jpg",
                                                "image_asset_id": null,
                                                "specification": {
                                                            "category": "Executive MBA (EMBA) Programs",
                                                            "qualification": "Executive MBA",
                                                            "listing_page": "PDF page 3",
                                                            "programme_count": "16"
                                                },
                                                "programs": [
                                                            {
                                                                        "title": "Executive MBA in Educational Leadership"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Media & Entertainment"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Global Banking & Finance"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Health & Safety Leadership"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Renewable Energy & Sustainability"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Tourism & Hospitality Management"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Innovation & Entrepreneurship"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Project Management"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Human Resources Management"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Supply Chain Management"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Health Care Management"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Engineering Management"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Public Administration"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Public Health"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Digital Marketing"
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Sport Management"
                                                            }
                                                ]
                                    }
                        ]
            },
            {
                        "key": "uca-global-mba",
                        "label": "UCA + Rushford — Global MBA",
                        "universities": [
                                    {
                                                "name": "University for the Creative Arts (UCA), UK + Rushford Business School, Switzerland",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/global-mba.jpg",
                                                "image_asset_id": null,
                                                "specification": {
                                                            "category": "Global MBA + Rushford Business School, Switzerland",
                                                            "qualification": "Global MBA",
                                                            "listing_page": "PDF page 4",
                                                            "programme_count": "1"
                                                },
                                                "programs": [
                                                            {
                                                                        "title": "Global MBA"
                                                            }
                                                ]
                                    }
                        ]
            }
]
MBA_JSON, true);

        $mastersUniversities = json_decode(<<<'MASTERS_JSON'
[
            {
                        "name": "Rushford Business School (RBS), Switzerland",
                        "logo": null,
                        "logo_asset_id": null,
                        "image": "assets/images/edutainment/international-students-university-campus-1.jpg",
                        "image_asset_id": null,
                        "specification": {
                                    "category": "Postgraduate Courses - MSc",
                                    "qualification": "MSc",
                                    "listing_page": "PDF page 1",
                                    "programme_count": "9"
                        },
                        "programs": [
                                    {
                                                "title": "MSc in Sustainability and Environmental Management"
                                    },
                                    {
                                                "title": "MSc in Strategic Management"
                                    },
                                    {
                                                "title": "MSc in Operations and Supply Chain Management"
                                    },
                                    {
                                                "title": "MSc in International Business Management"
                                    },
                                    {
                                                "title": "MSc in Marketing"
                                    },
                                    {
                                                "title": "MSc in Entrepreneurship & Innovation"
                                    },
                                    {
                                                "title": "MSc in Finance and Investment"
                                    },
                                    {
                                                "title": "MSc in Economics"
                                    },
                                    {
                                                "title": "MSc in Business Management"
                                    }
                        ]
            },
            {
                        "name": "Girne American University (GAU), North Cyprus",
                        "logo": null,
                        "logo_asset_id": null,
                        "image": "assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg",
                        "image_asset_id": null,
                        "specification": {
                                    "category": "MSc Programs (with Thesis)",
                                    "qualification": "MSc with Thesis",
                                    "listing_page": "PDF page 3",
                                    "programme_count": "4"
                        },
                        "programs": [
                                    {
                                                "title": "MSc in Business Management"
                                    },
                                    {
                                                "title": "MSc in Economics"
                                    },
                                    {
                                                "title": "MSc in Healthcare Management"
                                    },
                                    {
                                                "title": "MSc in Counselling Psychology"
                                    }
                        ]
            },
            {
                        "name": "University of Wolverhampton (UOW), UK",
                        "logo": null,
                        "logo_asset_id": null,
                        "image": "assets/images/edutainment/learning-beyond.png",
                        "image_asset_id": null,
                        "specification": {
                                    "category": "Master of Laws",
                                    "qualification": "Master of Laws",
                                    "listing_page": "PDF page 4",
                                    "programme_count": "1"
                        },
                        "programs": [
                                    {
                                                "title": "Master of Laws"
                                    }
                        ]
            }
]
MASTERS_JSON, true);

        $this->migrator->update(
            'mba_masters_mba.label',
            fn () => 'MBA specializations'
        );
        $this->migrator->update(
            'mba_masters_mba.heading',
            fn () => 'MBA programmes from the listing'
        );
        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'Explore the university categories and programme titles listed in the attached programme register. Open Specifications separately from the programme list.'
        );
        $this->migrator->update(
            'mba_masters_mba.tabs',
            fn () => $mbaTabs
        );

        $this->migrator->update(
            'mba_masters_masters.label',
            fn () => 'Master\'s programs'
        );
        $this->migrator->update(
            'mba_masters_masters.heading',
            fn () => 'Master\'s programmes from the listing'
        );
        $this->migrator->update(
            'mba_masters_masters.intro',
            fn () => 'Review only the Master\'s, MSc and Master of Laws entries listed in the attached programme register. Open Specifications separately from the programme list.'
        );
        $this->migrator->update(
            'mba_masters_masters.universities',
            fn () => $mastersUniversities
        );
    }
};

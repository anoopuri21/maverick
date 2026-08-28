<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $mbaTabs = json_decode(<<<'MBA_JSON'
[
            {
                        "key": "general",
                        "label": "General MBA",
                        "universities": [
                                    {
                                                "name": "Rushford Business School (RBS), Switzerland",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/general-mba.jpg",
                                                "image_asset_id": null,
                                                "programs": [
                                                            {
                                                                        "title": "Master of Business Administration (MBA)",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "General business administration",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Strategic Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Strategic Management",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Human Resource Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Human Resource Management",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Marketing",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Marketing",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Finance",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Finance",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            }
                                                ]
                                    },
                                    {
                                                "name": "Girne American University (GAU), North Cyprus",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/business-management-mba.jpg",
                                                "image_asset_id": null,
                                                "programs": [
                                                            {
                                                                        "title": "MBA in Business Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Business Management",
                                                                                    "duration": "12–15 months on the published GAU MBA route; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects and case studies",
                                                                                    "entry": "Bachelor’s degree, postgraduate/Level 7 qualification or relevant managerial experience depending on route",
                                                                                    "source": "docs/listing.pdf · page 2; official GAU MBA Business Management route reference",
                                                                                    "source_status": "Programme listed in PDF · published GAU route reference, final details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Financial Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Financial Management",
                                                                                    "duration": "12–15 months on the published GAU MBA route; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects and case studies",
                                                                                    "entry": "Bachelor’s degree, postgraduate/Level 7 qualification or relevant managerial experience depending on route",
                                                                                    "source": "docs/listing.pdf · page 2; official GAU MBA Business Management route reference",
                                                                                    "source_status": "Programme listed in PDF · published GAU route reference, final details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in International Business Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "International Business Management",
                                                                                    "duration": "12–15 months on the published GAU MBA route; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects and case studies",
                                                                                    "entry": "Bachelor’s degree, postgraduate/Level 7 qualification or relevant managerial experience depending on route",
                                                                                    "source": "docs/listing.pdf · page 2; official GAU MBA Business Management route reference",
                                                                                    "source_status": "Programme listed in PDF · published GAU route reference, final details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Management Information Systems",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Management Information Systems",
                                                                                    "duration": "12–15 months on the published GAU MBA route; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects and case studies",
                                                                                    "entry": "Bachelor’s degree, postgraduate/Level 7 qualification or relevant managerial experience depending on route",
                                                                                    "source": "docs/listing.pdf · page 2; official GAU MBA Business Management route reference",
                                                                                    "source_status": "Programme listed in PDF · published GAU route reference, final details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Marketing",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Marketing",
                                                                                    "duration": "12–15 months on the published GAU MBA route; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects and case studies",
                                                                                    "entry": "Bachelor’s degree, postgraduate/Level 7 qualification or relevant managerial experience depending on route",
                                                                                    "source": "docs/listing.pdf · page 2; official GAU MBA Business Management route reference",
                                                                                    "source_status": "Programme listed in PDF · published GAU route reference, final details require confirmation"
                                                                        }
                                                            }
                                                ]
                                    }
                        ]
            },
            {
                        "key": "specialized",
                        "label": "Specialized MBA",
                        "universities": [
                                    {
                                                "name": "Rushford Business School (RBS), Switzerland",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/specialized-mba.jpg",
                                                "image_asset_id": null,
                                                "programs": [
                                                            {
                                                                        "title": "MBA in Sustainability, Energy and Environment",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Sustainability, Energy and Environment",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Real Estate Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Real Estate Management",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Logistics & Supply Chain Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Logistics & Supply Chain Management",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Healthcare Leadership",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Healthcare Leadership",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Hospitality & Tourism Management",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Hospitality & Tourism Management",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Health Economics",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Health Economics",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "MBA in Entrepreneurship and Innovation",
                                                                        "specification": {
                                                                                    "qualification": "MBA",
                                                                                    "partner": "Rushford Business School (RBS), Switzerland",
                                                                                    "focus": "Entrepreneurship and Innovation",
                                                                                    "duration": "10–12 months on the published accelerated route; confirm the selected programme",
                                                                                    "delivery": "100% online on the published SWISS Global MBA route; confirm the selected programme",
                                                                                    "assessment": "Assignments, projects, case studies and research project/dissertation where applicable",
                                                                                    "entry": "Recognised bachelor’s degree; route-specific exemptions and English evidence to be confirmed",
                                                                                    "source": "docs/listing.pdf · page 1; official Rushford/SWISS Global MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            }
                                                ]
                                    }
                        ]
            },
            {
                        "key": "executive",
                        "label": "Executive MBA",
                        "universities": [
                                    {
                                                "name": "Girne American University (GAU), North Cyprus",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/executive-mba.jpg",
                                                "image_asset_id": null,
                                                "programs": [
                                                            {
                                                                        "title": "Executive MBA in Educational Leadership",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Educational Leadership",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Media & Entertainment",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Media & Entertainment",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Global Banking & Finance",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Global Banking & Finance",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Health & Safety Leadership",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Health & Safety Leadership",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Renewable Energy & Sustainability",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Renewable Energy & Sustainability",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Tourism & Hospitality Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Tourism & Hospitality Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Innovation & Entrepreneurship",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Innovation & Entrepreneurship",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Project Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Project Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Human Resources Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Human Resources Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Supply Chain Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Supply Chain Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Health Care Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Health Care Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Engineering Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Engineering Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Public Administration",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Public Administration",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Public Health",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Public Health",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Digital Marketing",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Digital Marketing",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            },
                                                            {
                                                                        "title": "Executive MBA in Sport Management",
                                                                        "specification": {
                                                                                    "qualification": "Executive MBA",
                                                                                    "partner": "Girne American University (GAU), North Cyprus",
                                                                                    "focus": "Sport Management",
                                                                                    "duration": "12–18 months on the published UAE Executive MBA guide reference; confirm the selected programme",
                                                                                    "delivery": "Online, hybrid or part-time depending on the selected route",
                                                                                    "assessment": "Assignments, projects, case studies and/or research; confirm the selected route",
                                                                                    "entry": "Professional and academic profile requirements vary by route; confirm with admissions",
                                                                                    "source": "docs/listing.pdf · page 3; official Maverick UAE Executive MBA route reference",
                                                                                    "source_status": "Programme listed in PDF · route details require confirmation"
                                                                        }
                                                            }
                                                ]
                                    }
                        ]
            },
            {
                        "key": "global",
                        "label": "Global MBA",
                        "universities": [
                                    {
                                                "name": "University for the Creative Arts (UCA), UK + Rushford Business School, Switzerland",
                                                "logo": null,
                                                "logo_asset_id": null,
                                                "image": "assets/images/mba-masters-landing/mba/global-mba.jpg",
                                                "image_asset_id": null,
                                                "programs": [
                                                            {
                                                                        "title": "Global MBA",
                                                                        "specification": {
                                                                                    "qualification": "Global MBA",
                                                                                    "partner": "University for the Creative Arts (UCA), UK + Rushford Business School, Switzerland",
                                                                                    "focus": "Global business management",
                                                                                    "duration": "12–15 months maximum on the published Global MBA route",
                                                                                    "delivery": "100% online on the published Global MBA route",
                                                                                    "assessment": "180 credits: six 20-credit modules plus a 60-credit dissertation research project",
                                                                                    "entry": "Bachelor’s degree or approved Level 7 route; mature-entry work experience may apply",
                                                                                    "source": "docs/listing.pdf · page 4; official Maverick Global MBA route reference",
                                                                                    "source_status": "Programme and partner route listed in PDF · current awarding arrangement to confirm"
                                                                        }
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
                        "name": "Rushford Business School (RBS), Switzerland — MSc",
                        "logo": null,
                        "logo_asset_id": null,
                        "image": "assets/images/edutainment/international-students-university-campus-1.jpg",
                        "image_asset_id": null,
                        "programs": [
                                    {
                                                "title": "MSc in Sustainability and Environmental Management",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Sustainability and Environmental Management",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Strategic Management",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Strategic Management",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Operations and Supply Chain Management",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Operations and Supply Chain Management",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in International Business Management",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "International Business Management",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Marketing",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Marketing",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Entrepreneurship & Innovation",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Entrepreneurship & Innovation",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Finance and Investment",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Finance and Investment",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Economics",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Economics",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Business Management",
                                                "specification": {
                                                            "qualification": "MSc",
                                                            "partner": "Rushford Business School (RBS), Switzerland",
                                                            "focus": "Business Management",
                                                            "duration": "15–18 months on published MSc route; shorter top-up/dissertation routes may apply where eligible",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, case studies and dissertation/research work where applicable",
                                                            "entry": "Bachelor’s degree, HND or approved Level 7 route depending on programme",
                                                            "source": "docs/listing.pdf · page 1; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · route details require confirmation"
                                                }
                                    }
                        ]
            },
            {
                        "name": "Girne American University (GAU), North Cyprus — MSc with Thesis",
                        "logo": null,
                        "logo_asset_id": null,
                        "image": "assets/images/edutainment/dubai-uae-skyline-students-studying-camp-1.jpg",
                        "image_asset_id": null,
                        "programs": [
                                    {
                                                "title": "MSc in Business Management",
                                                "specification": {
                                                            "qualification": "MSc with Thesis",
                                                            "partner": "Girne American University (GAU), North Cyprus",
                                                            "focus": "Business Management",
                                                            "duration": "15–18 months on published MSc route; confirm the selected thesis pathway",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, research and thesis/dissertation work where applicable",
                                                            "entry": "Academic and professional requirements vary by thesis route; confirm with admissions",
                                                            "source": "docs/listing.pdf · page 3; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · thesis-route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Economics",
                                                "specification": {
                                                            "qualification": "MSc with Thesis",
                                                            "partner": "Girne American University (GAU), North Cyprus",
                                                            "focus": "Economics",
                                                            "duration": "15–18 months on published MSc route; confirm the selected thesis pathway",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, research and thesis/dissertation work where applicable",
                                                            "entry": "Academic and professional requirements vary by thesis route; confirm with admissions",
                                                            "source": "docs/listing.pdf · page 3; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · thesis-route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Healthcare Management",
                                                "specification": {
                                                            "qualification": "MSc with Thesis",
                                                            "partner": "Girne American University (GAU), North Cyprus",
                                                            "focus": "Healthcare Management",
                                                            "duration": "15–18 months on published MSc route; confirm the selected thesis pathway",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, research and thesis/dissertation work where applicable",
                                                            "entry": "Academic and professional requirements vary by thesis route; confirm with admissions",
                                                            "source": "docs/listing.pdf · page 3; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · thesis-route details require confirmation"
                                                }
                                    },
                                    {
                                                "title": "MSc in Counselling Psychology",
                                                "specification": {
                                                            "qualification": "MSc with Thesis",
                                                            "partner": "Girne American University (GAU), North Cyprus",
                                                            "focus": "Counselling Psychology",
                                                            "duration": "15–18 months on published MSc route; confirm the selected thesis pathway",
                                                            "delivery": "Online, hybrid or part-time depending on the selected route",
                                                            "assessment": "Assignments, projects, research and thesis/dissertation work where applicable",
                                                            "entry": "Academic and professional requirements vary by thesis route; confirm with admissions",
                                                            "source": "docs/listing.pdf · page 3; official Maverick MSc route references",
                                                            "source_status": "Programme listed in PDF · thesis-route details require confirmation"
                                                }
                                    }
                        ]
            },
            {
                        "name": "University of Wolverhampton (UOW), UK",
                        "logo": null,
                        "logo_asset_id": null,
                        "image": "assets/images/edutainment/learning-beyond.png",
                        "image_asset_id": null,
                        "programs": [
                                    {
                                                "title": "Master of Laws",
                                                "specification": {
                                                            "qualification": "Master of Laws (LLM)",
                                                            "partner": "University of Wolverhampton (UOW), UK",
                                                            "focus": "Law",
                                                            "duration": "Confirm with admissions",
                                                            "delivery": "Confirm with admissions",
                                                            "assessment": "Confirm with admissions",
                                                            "entry": "Confirm with admissions",
                                                            "source": "docs/listing.pdf · page 4",
                                                            "source_status": "Programme and university listed in PDF · full route specification not supplied"
                                                }
                                    }
                        ]
            }
]
MASTERS_JSON, true);

        $this->migrator->update(
            'mba_masters_mba.heading',
            fn () => 'MBA programmes by route and partner'
        );
        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'Review the MBA, Executive MBA and Global MBA routes listed in our current programme catalogue. Open each programme to see its working specification, then confirm the final route with admissions.'
        );
        $this->migrator->update(
            'mba_masters_mba.stage_image',
            fn () => 'assets/images/mba-masters-landing/mba/mba-stage.jpg'
        );
        $this->migrator->update(
            'mba_masters_mba.tabs',
            fn () => $mbaTabs
        );

        $this->migrator->update(
            'mba_masters_masters.heading',
            fn () => 'Master’s and MSc programmes by partner'
        );
        $this->migrator->update(
            'mba_masters_masters.intro',
            fn () => 'Review the Master’s, MSc and LLM routes listed in the attached programme register. Published information is separated from details that must be confirmed for your selected route.'
        );
        $this->migrator->update(
            'mba_masters_masters.universities',
            fn () => $mastersUniversities
        );
    }
};

"""Offline regression checks for approved pages and separate admin-only drafts."""
import csv
import contextlib
import io
import json
import tempfile
import unittest
from pathlib import Path
from unittest import mock

from tools import build_catalog_data as builder
from tools import dedupe_scan
from tools import validate_catalog_data as validator


class CatalogValidationTest(unittest.TestCase):
    def run_with_files(self, files):
        output = io.StringIO()
        with mock.patch.object(validator, "FILES", files), contextlib.redirect_stdout(output):
            status = validator.main()
        return status, output.getvalue()

    def with_modified_draft_php(self, old, new):
        content = (Path(validator.DATA_DIR) / "bachelors_admin_drafts.php").read_text(encoding="utf-8")
        self.assertIn(old, content)
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "bachelors_admin_drafts.php"
            path.write_text(content.replace(old, new, 1), encoding="utf-8")
            return self.run_with_files(validator.FILES[:3] + [str(path)])

    def test_current_data_passes(self):
        status, output = self.run_with_files(validator.FILES)
        self.assertEqual(status, 0)
        self.assertIn("70 programme(s) across 4 file(s)", output)
        self.assertIn("Bachelor source gate: 5 approved pages, 4 incomplete tracker listings, "
                      "3 inactive admin drafts (8 Bachelor seed rows total)", output)

    def test_admin_draft_copy_has_no_stylistic_overlap_with_other_drafts_or_approved_pages(self):
        seen, stylistic = {}, set()
        pages = Path(validator.ROOT) / "output/programs"
        for path in pages.glob("*/*/*.md"):
            prose = " ".join(dedupe_scan.prose_blocks(path.read_text(encoding="utf-8")))
            seen[str(path)] = dedupe_scan.shingles(dedupe_scan.norm(prose).split(), 8)
        data = json.loads(Path(validator.ADMIN_SOURCE).read_text(encoding="utf-8"))
        draft_slugs = set()
        for row in data["drafts"]:
            draft_slugs.add(row["slug"])
            prose = " ".join([row["hero"], *row["overview"]])
            seen[row["slug"]] = dedupe_scan.shingles(dedupe_scan.norm(prose).split(), 8)
        owners = {}
        for name, shingles in seen.items():
            for shingle in shingles:
                owners.setdefault(shingle, set()).add(name)
        for shingle, names in owners.items():
            if len(names) > 1 and names & draft_slugs and not any(
                marker in shingle for marker in dedupe_scan.FACTUAL_MARKERS
            ):
                stylistic.add(shingle)
        self.assertEqual(set(), stylistic)

    def test_admin_drafts_cannot_start_active_or_masquerade_as_approved_pages(self):
        status, output = self.with_modified_draft_php("'is_active' => false", "'is_active' => true")
        self.assertEqual(status, 1)
        self.assertIn("new catalogue rows must start inactive", output)

        status, output = self.with_modified_draft_php("'admin_draft' => true", "'admin_draft' => false")
        self.assertEqual(status, 1)
        self.assertIn("wrong admin_draft marker", output)

    def test_admin_draft_cannot_gain_an_unverified_honours_award_or_fee(self):
        status, output = self.with_modified_draft_php(
            "'title' => 'BA Global Business (Level 9)'",
            "'title' => 'BA (Hons) in Global Business'",
        )
        self.assertEqual(status, 1)
        self.assertIn("admin draft title differs from official source", output)

        status, output = self.with_modified_draft_php("'meta_title' => ''", "'meta_title' => 'Invented SEO'")
        self.assertEqual(status, 1)
        self.assertIn("admin draft meta_title must stay blank", output)

        status, output = self.with_modified_draft_php(
            "'fees' => [\n            ]", "'fees' => [\n                '£999',\n            ]",
        )
        self.assertEqual(status, 1)
        self.assertIn("admin draft fees must stay blank", output)

    def test_draft_source_rejects_agent_urls_and_the_fourth_accounting_award(self):
        data = json.loads(Path(validator.ADMIN_SOURCE).read_text(encoding="utf-8"))
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "drafts.json"
            data["drafts"][0]["source_urls"] = ["https://agents.example/gau/course"]
            path.write_text(json.dumps(data), encoding="utf-8")
            with self.assertRaisesRegex(ValueError, "only official university URLs"):
                builder.load_admin_drafts(str(path))
            with mock.patch.object(validator, "ADMIN_SOURCE", str(path)):
                status, output = self.run_with_files(validator.FILES)
            self.assertEqual(status, 1)
            self.assertIn("only official university URLs", output)

            data = json.loads(Path(validator.ADMIN_SOURCE).read_text(encoding="utf-8"))
            data["drafts"][0]["tracker_sno"] = 75
            path.write_text(json.dumps(data), encoding="utf-8")
            with self.assertRaisesRegex(ValueError, "Unrecognised or duplicate"):
                builder.load_admin_drafts(str(path))
            with mock.patch.object(validator, "ADMIN_SOURCE", str(path)):
                status, output = self.run_with_files(validator.FILES)
            self.assertEqual(status, 1)
            self.assertIn("unexpected/duplicate tracker row 75", output)

    def test_tracker_keeps_original_bom_columns_and_crlf(self):
        raw = Path(validator.TRACKER).read_bytes()
        self.assertTrue(raw.startswith(b"\xef\xbb\xbf"))
        self.assertEqual(raw.count(b"\n"), raw.count(b"\r\n"))
        with open(validator.TRACKER, encoding="utf-8-sig", newline="") as source:
            reader = csv.DictReader(source)
            self.assertEqual(12, len(reader.fieldnames or []))
            rows = {row["S.No"]: row for row in reader}
        self.assertEqual("BSc Accounting", rows["75"]["Program Name (as in listing)"])
        self.assertEqual("", rows["75"]["Output File"])
        self.assertEqual("", rows["72"]["Output File"])
        self.assertEqual(validator.ADMIN_STATUS, rows["72"]["Content Status"])

    def test_missing_bachelors_file_is_an_error(self):
        with tempfile.TemporaryDirectory() as folder:
            files = validator.FILES[:2] + [str(Path(folder) / "bachelors_programs.php")] + validator.FILES[3:]
            status, output = self.run_with_files(files)
        self.assertEqual(status, 1)
        self.assertIn("missing required catalogue data file", output)

    def test_missing_admin_draft_file_fails_closed(self):
        with tempfile.TemporaryDirectory() as folder:
            files = validator.FILES[:3] + [str(Path(folder) / "bachelors_admin_drafts.php")]
            status, output = self.run_with_files(files)
        self.assertEqual(status, 1)
        self.assertIn("missing required catalogue data file", output)

    def test_new_rows_cannot_start_active(self):
        bachelor = Path(validator.DATA_DIR) / "bachelors_programs.php"
        content = bachelor.read_text(encoding="utf-8")
        self.assertIn("'is_active' => false", content)
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "bachelors_programs.php"
            path.write_text(content.replace("'is_active' => false", "'is_active' => true", 1), encoding="utf-8")
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)] + validator.FILES[3:])
        self.assertEqual(status, 1)
        self.assertIn("new catalogue rows must start inactive", output)

    def test_empty_seed_file_is_an_error(self):
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "bachelors_programs.php"
            path.write_text("<?php return ['categories' => [], 'universities' => [], 'programs' => []];\n")
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)] + validator.FILES[3:])
        self.assertEqual(status, 1)
        self.assertIn("no programme rows", output)

    def test_unapproved_bachelor_cannot_replace_an_approved_slug(self):
        bachelor = Path(validator.DATA_DIR) / "bachelors_programs.php"
        content = bachelor.read_text(encoding="utf-8")
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "bachelors_programs.php"
            path.write_text(content.replace(
                "'slug' => 'bsc-tourism-and-hospitality-management-girne-american-university'",
                "'slug' => 'bsc-accounting-girne-american-university'", 1,
            ), encoding="utf-8")
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)] + validator.FILES[3:])
        self.assertEqual(status, 1)
        self.assertIn("unapproved Bachelor seed slug", output)
        self.assertIn("approved Bachelor missing from seed data", output)

    def test_bachelor_title_cannot_drift_to_another_award(self):
        bachelor = Path(validator.DATA_DIR) / "bachelors_programs.php"
        content = bachelor.read_text(encoding="utf-8")
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "bachelors_programs.php"
            path.write_text(content.replace(
                "'title' => 'BSc Tourism and Hospitality Management'",
                "'title' => 'BSc Accounting and Finance Management'", 1,
            ), encoding="utf-8")
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)] + validator.FILES[3:])
        self.assertEqual(status, 1)
        self.assertIn("Bachelor seed title differs from approved tracker title", output)

    def test_incomplete_bachelor_cannot_be_marked_approved_by_csv_only(self):
        with open(validator.TRACKER, encoding="utf-8-sig", newline="") as source:
            reader = csv.DictReader(source)
            columns, rows = reader.fieldnames, list(reader)
        for row in rows:
            if row["S.No"] == "74":
                row["Content Status"] = "Completed"
                row["Output File"] = (
                    "output/programs/bachelors/girne-american-university/"
                    "bsc-human-resources-management-girne-american-university.md"
                )
                break
        else:
            self.fail("Tracker row 74 is missing")
        with tempfile.TemporaryDirectory() as folder:
            tracker = Path(folder) / "tracker.csv"
            with tracker.open("w", encoding="utf-8-sig", newline="") as target:
                writer = csv.DictWriter(target, fieldnames=columns, lineterminator="\r\n")
                writer.writeheader()
                writer.writerows(rows)
            with mock.patch.object(validator, "TRACKER", str(tracker)):
                status, output = self.run_with_files(validator.FILES)
        self.assertEqual(status, 1)
        self.assertIn("incomplete research cannot have approved content", output)

    def test_missing_bachelor_tracker_is_an_error(self):
        with tempfile.TemporaryDirectory() as folder:
            with mock.patch.object(validator, "TRACKER", str(Path(folder) / "missing.csv")):
                status, output = self.run_with_files(validator.FILES)
        self.assertEqual(status, 1)
        self.assertIn("Bachelor tracker: cannot read", output)


if __name__ == "__main__":
    unittest.main()

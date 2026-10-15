"""Offline regression checks for the three generated catalogue seed files."""
import csv
import contextlib
import io
import tempfile
import unittest
from pathlib import Path
from unittest import mock

from tools import validate_catalog_data as validator


class CatalogValidationTest(unittest.TestCase):
    def run_with_files(self, files):
        output = io.StringIO()
        with mock.patch.object(validator, "FILES", files), contextlib.redirect_stdout(output):
            status = validator.main()
        return status, output.getvalue()

    def test_current_data_passes(self):
        status, output = self.run_with_files(validator.FILES)
        self.assertEqual(status, 0)
        self.assertIn("67 programme(s) across 3 file(s)", output)
        self.assertIn("Bachelor source gate: 5 approved, 4 incomplete, 5 seeded", output)

    def test_missing_bachelors_file_is_an_error(self):
        with tempfile.TemporaryDirectory() as folder:
            files = validator.FILES[:2] + [str(Path(folder) / "bachelors_programs.php")]
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
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)])
        self.assertEqual(status, 1)
        self.assertIn("new catalogue rows must start inactive", output)

    def test_empty_seed_file_is_an_error(self):
        with tempfile.TemporaryDirectory() as folder:
            path = Path(folder) / "bachelors_programs.php"
            path.write_text("<?php return ['categories' => [], 'universities' => [], 'programs' => []];\n")
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)])
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
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)])
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
            status, output = self.run_with_files(validator.FILES[:2] + [str(path)])
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

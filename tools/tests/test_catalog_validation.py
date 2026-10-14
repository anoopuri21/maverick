"""Offline regression checks for the three generated catalogue seed files."""
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


if __name__ == "__main__":
    unittest.main()

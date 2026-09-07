# Faculty Insights — seed images

`uploads/faculty-insights.pdf` se nikali hui 8 photos, sab **900 × 990 px JPG**
(card ratio 3:3.3, face top-aligned, quality 88).

> Note: PDF me photos 1–4 chhoti resolution (~340–440px) me embedded thi,
> isliye unhe upscale kiya gaya hai — thodi soft dikhengi. Ho sake to
> owner se original high-res photos mangwa lein (advise, not blocker).

## Upload steps (manual, Admin Panel)
1. Server par seed chalao: `php artisan db:seed --class=Database\Seeders\FacultyInsightSeeder`
2. Filament → **Homepage → Faculty Insights** → faculty row **Edit**
3. **Featured Image** → **Upload New** → neeche wali matching file select karo
4. Save (cache auto-flush ho jayega)

## Mapping (PDF position order = sort order)
| # | Faculty (title) | Image file |
|---|---|---|
| 1 | Prof. Dr. David Mark Holliman | `faculty-01-david-holliman.jpg` |
| 2 | Prof. Dr. Othman Abu Khurma | `faculty-02-othman-abu-khurma.jpg` |
| 3 | Prof. Steve Alban Tineo | `faculty-03-steve-alban-tineo.jpg` |
| 4 | Prof. Alexander Schmidt | `faculty-04-alexander-schmidt.jpg` |
| 5 | Prof. Veronika Knight | `faculty-05-veronika-knight.jpg` |
| 6 | Shyma Mohaisen | `faculty-06-shyma-mohaisen.jpg` |
| 7 | Prof. Dr. Shanmugan Joghee | `faculty-07-shanmugan-joghee.jpg` |
| 8 | Prof. Yana Nikitina | `faculty-08-yana-nikitina.jpg` |

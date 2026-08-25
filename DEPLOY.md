# Élesítés

## Egyszeri lépések

1. `composer install --no-dev --optimize-autoloader`
2. `.env`: `APP_ENV=production`, `APP_DEBUG=false`

   > `APP_DEBUG=false` nélkül az oldal a **tömörítetlen** CSS/JS-t szolgálja ki.
   > Ez szándékos: fejlesztés közben a forrásfájlt akarjuk látni.

3. **Közösségi profilok** — `config/social.php`, vagy `.env`:

   ```
   SOCIAL_INSTAGRAM=https://www.instagram.com/<felhasznalonev>
   SOCIAL_MESSENGER=https://m.me/<oldal>
   SOCIAL_FIVERR=https://www.fiverr.com/<felhasznalonev>
   ```

   Amelyik üresen marad, az **nem jelenik meg** sem a láblécben, sem a
   kapcsolat oldalon, sem a `sameAs` strukturált adatban. Nincs törött link.

## Minden kiadásnál

```bash
php artisan assets:build      # .min.css / .min.js legyártása
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Az `assets:build` **nem kötelező**: ha nem fut le, az oldal az eredeti
fájlokat szolgálja ki — lassabban, de hibátlanul.

Az eszköz-URL-ek `?v=<módosítási idő>` bélyeget kapnak, ezért a
`public/.htaccess`-ben beállított egyéves gyorsítótár biztonságos: ha
változik a fájl, változik az URL is.

## Amit érdemes tudni

- `storage/source-media/` — a nyers, feldolgozatlan feltöltések (nagy méretű
  videó és PNG). Nincs kiszolgálva, szándékosan a `public/` mappán kívül van.
- A videó `Accept-Ranges` fejlécet kap az `.htaccess`-ből, hogy tekerni
  lehessen benne. A `php artisan serve` ezt **nem** támogatja, ezért a
  tekerés csak éles kiszolgálón (Apache/nginx) működik.

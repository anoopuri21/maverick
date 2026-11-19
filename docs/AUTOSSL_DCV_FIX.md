# AutoSSL fail — `mbalondon.org.uk` (Let's Encrypt DCV 404 + NXDOMAIN)

Error jo aapko mila:

```
MASTER DCV: 403 urn:ietf:params:acme:error:unauthorized
  (During secondary validation: 212.8.252.67: Invalid response from
   https://mbalondon.org.uk/.well-known/acme-challenge/<token>: 404)
  400 urn:ietf:params:acme:error:dns
  (DNS problem: NXDOMAIN looking up TXT for _acme-challenge.mbalondon.org.uk)
```

## Iska matlab (decode)

cPanel AutoSSL ne certificate issue karne ke liye **domain control validate (DCV)** karne ki koshish ki.
Isme **do alag methods** dikhe rahe hain, **dono fail** hue:

1. **HTTP-01 validation → 404**
   Let's Encrypt ka validator `http(s)://mbalondon.org.uk/.well-known/acme-challenge/<token>`
   fetch karta hai. Usko **404** mila. `212.8.252.67` Let's Encrypt ka **secondary/perspective
   validator** hai (multi-perspective check) — yaani problem sirf aapke yahan nahi, sab jagah se 404 aa raha tha.

2. **DNS-01 validation → NXDOMAIN**
   `_acme-challenge.mbalondon.org.uk` ka **TXT record exist hi nahi karta**. Isliye DNS method bhi fail.

Certificate issue hone ke liye **kam se kam ek method pass hona chahiye**. Sabse aasaan **HTTP-01** theek karna hai.

> ⚠️ Zaroori: **HTTP-01 tabhi pass hoga jab `mbalondon.org.uk` is MilesWeb server ko point kare
> aur docroot `public/` pe ho.** Agar pichhla 404 (docroot) issue abhi bhi hai, to SSL kabhi nahi banega —
> pehle `docs/ADDON_DOMAIN_404_FIX.md` ka Step 1 + Step 2 poora karo.

---

## Fix — HTTP-01 (recommended, order me karo)

### A. DNS: domain is server pe resolve hona chahiye
```bash
dig +short A mbalondon.org.uk
dig +short A www.mbalondon.org.uk
```
- Dono ka IP **isi MilesWeb server ka shared IP** hona chahiye (cPanel → right side "Shared IP Address",
  ya **Zone Editor** me A record dekho).
- Agar IP galat/khaali hai → **Zone Editor** me A record `@` aur `www` server ke shared IP pe set karo,
  ya registrar ke nameservers MilesWeb ke NS pe point karo. Propagate hone do (kuch minute–ghante).
- **NXDOMAIN TXT** ka matlab aksar yeh hota hai ki domain ka DNS **kahin aur** host ho raha hai
  (Cloudflare / registrar). Us surat me cPanel DNS-01 ka TXT record nahi bana sakta → HTTP-01 hi rely karo,
  ya DNS host pe manually TXT record add karo (niche "DNS-01" section).

### B. Docroot `public/` pe ho (pichhla fix)
cPanel → **Domains** → `mbalondon.org.uk` → Document Root = `/home/USER/demo.vsinfosys.in/public`.

### C. `.htaccess` — ACME challenge ko Laravel me jaane se roko (repo me already add kar diya)
Laravel ka default `.htaccess` har non-file request ko `index.php` pe bhejta hai. Isse
`/.well-known/acme-challenge/<token>` bhi Laravel 404 ban jaata tha. Maine `public/.htaccess` me
yeh line front-controller rule se **pehle** add kar di hai:

```apache
RewriteRule ^\.well-known/acme-challenge/ - [L]
```

Isse ACME challenge static file ki tarah serve hota hai, Laravel route nahi banta.
Server pe **deploy** karna mat bhoolna:

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
cd /home/USER/demo.vsinfosys.in
git pull origin main          # ya public/.htaccess manually edit karo
php artisan optimize:clear
```

### D. Challenge path actually serve ho raha hai — test karo
cPanel AutoSSL ke waqt challenge file docroot me hoti hai. Manually test:

```bash
# docroot ke andar test file banao
echo "ok" > /home/USER/demo.vsinfosys.in/public/.well-known/acme-challenge/test.txt

# bahar se fetch (aapke local machine se)
curl -i http://mbalondon.org.uk/.well-known/acme-challenge/test.txt
```
- **200 + "ok"** aaye → path theek hai, ab AutoSSL Run karo.
- **404** aaye → docroot ya DNS abhi bhi galat hai (A/B dobara check karo).
- Test ke baad `test.txt` **delete** kar dena.

> HTTP-01 challenge **port 80 (HTTP)** pe validate hota hai. `mbalondon.org.uk` → `www` ya HTTP→HTTPS
> ka koi **redirect mat rakho** jab tak cert issue na ho jaye (redirect validator ko token tak pahunchne se rok deta hai).

### E. AutoSSL dobara chalao
cPanel → **SSL/TLS Status** → `mbalondon.org.uk` + `www.mbalondon.org.uk` select → **Run AutoSSL**.
Green lock aane tak wait karo (kuch minute).

---

## Alternative — DNS-01 (agar domain ka DNS kahin aur hai)

Agar `mbalondon.org.uk` ka DNS Cloudflare/registrar pe hai aur HTTP-01 mushkil hai:

1. Apne **DNS provider** (jahan NS point hain) me ek **TXT record** add karo:
   - Host: `_acme-challenge`
   - Value: jo token cPanel/AutoSSL de (DNS-01 select karne par cPanel value dikhata hai)
2. Ya cPanel → **SSL/TLS** → provider settings me **DNS-01** enable karo (host allow kare to).

Zyadatar shared hosting me **HTTP-01 hi default aur sabse aasaan** hai — DNS theek ho to usi se kaam chal jayega.

---

## Quick checklist

- [ ] `dig +short A mbalondon.org.uk` → is server ka shared IP
- [ ] Docroot = `/home/USER/demo.vsinfosys.in/public`
- [ ] `public/.htaccess` me ACME exclusion line **deploy** ho gayi (`git pull` / manual)
- [ ] `curl -i http://mbalondon.org.uk/.well-known/acme-challenge/test.txt` → 200
- [ ] Koi `@ → www` / HTTP→HTTPS redirect **temporarily hata diya**
- [ ] SSL/TLS Status → **Run AutoSSL** → green lock

Sabse pehla kaam: **confirm karo `mbalondon.org.uk` is server ko point karta hai aur `public/` se serve ho raha hai.**
404 (docroot) theek hote hi AutoSSL apne aap pass ho jayega.

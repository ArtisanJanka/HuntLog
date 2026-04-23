# HuntLog

HuntLog ir tīmekļa platforma, kas paredzēta medību notikumu organizēšanai, pārvaldīšanai un informācijas apmaiņai. Tā nodrošina pārskatāmu un ērtu vidi, kurā lietotāji var apskatīt plānotās medības un pieteikties dalībai, savukārt medību vadītāji var izveidot notikumus, norādīt medību teritorijas kartē un pārvaldīt ar tiem saistīto informāciju. Platformā ir iekļauta arī administratora sadaļa lietotāju, satura un sistēmas datu pārvaldībai.

## Galvenās lomas

### Lietotājs (User)
Reģistrēts lietotājs var apskatīt plānotos medību notikumus, iepazīties ar pieejamo informāciju un pieteikties dalībai.

### Vadītājs (Leader)
Vadītājs var izveidot un pārvaldīt medību notikumus, definēt teritorijas, atjaunināt informāciju par notikumiem un pārraudzīt dalībnieku informāciju.

### Administrators (Admin)
Administrators nodrošina pilnu piekļuvi platformas pārvaldībai, tostarp lietotāju administrēšanai, satura kontrolei un sistēmas uzraudzībai.

## Funkcionalitāte

- Lietotāju autentifikācija un piekļuve pēc lomām
- Medību notikumu pārlūkošana un pārvaldība
- Pieteikšanās plūsma dalībniekiem
- Rīki vadītājiem notikumu izveidei un rediģēšanai
- Teritoriju izvēle un kartē balstīta notikumu informācija
- Administratora panelis lietotāju un satura pārvaldībai
- Failu un multivides satura atbalsts
- Vairāku valodu struktūras atbalsts

## Tehnoloģiju steks

HuntLog ir izstrādāts, izmantojot šādas tehnoloģijas:

- **Laravel** — servera puses ietvars
- **React** — lietotāja saskarne
- **Inertia.js** — savienojums starp Laravel un React
- **Vite** — frontend būvēšanas rīks
- **Tailwind CSS** — stilu veidošanai
- **MySQL / MariaDB** — datubāze
- **PHP** — servera puses izpildvide

### Prasības

Pirms projekta palaišanas pārliecinies, ka tev ir:

- PHP 8+
- Composer
- Node.js un npm
- MySQL vai MariaDB
- Lokāla izstrādes vide, piemēram, Laragon, XAMPP vai Laravel Valet

### Instalēšana

1. Klonē repozitoriju:

```bash
git clone <your-repository-url>
cd huntlog
```

2. Instalē backend atkarības:

```bash
composer install
```

3. Instalē frontend atkarības:

```bash
npm install
```

4. Izveido vides failu:

```bash
cp .env.example .env
```

5. Ģenerē lietotnes atslēgu:

```bash
php artisan key:generate
```

6. Konfigurē datubāzes iestatījumus `.env` failā.

7. Palaid datubāzes migrācijas:

```bash
php artisan migrate
```

8. Izveido storage simbolisko saiti:

```bash
php artisan storage:link
```

9. Palaid izstrādes serverus:

```bash
php artisan serve
npm run dev
```

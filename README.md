# Black CRM

## is a minimalistic insurance oriented CRM that stores and manages customer info as well as their contracts

## Tech & Tools:

-   Laravel (v10 base)
-   Breeze/Inertia Scaffolding
-   Vue 3: Composition API with `script setup`
-   Tailwind CSS
-   [DaisyUI](https://daisyui.com/)
-   [SweetAlert](https://sweetalert2.github.io/)
-   [HeadlessUI](https://headlessui.com/)
-   [Vuetify v3](https://vuetifyjs.com/en/)

## Features:

-   Functional design
-   Utilizes Polymorphic Relations
-   Support for file uploads
-   Elegant Responsive UI
-   Scope Based dynamic DB Filtering
-   Ability to export filtered results as a CSV file

## Code Style/Format Treatment

[Laravel Pint](https://laravel.com/docs/10.x/pint) with stock config & [Prettier](https://prettier.io/)

## Local Dev Env Setup

### Install Dependencies

```bash
composer install
npm install
```

### Set Environment Variables

```
cp .env.example .env
```

### Migrations

```bash
php artisan migrate --seed
```

### Run Dev Server

```bash
php artisan serve
```

### Compile Vue.js

```bash
npm run dev
```

### Storage Symlink

```bash
php artisan storage:link
```

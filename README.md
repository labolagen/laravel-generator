Labolagen
==========================

[![Total Downloads](https://poser.pugx.org/labolagen/laravel-generator/downloads)](https://packagist.org/packages/labolagen/laravel-generator)
[![Monthly Downloads](https://poser.pugx.org/labolagen/laravel-generator/d/monthly)](https://packagist.org/packages/labolagen/laravel-generator)
[![Daily Downloads](https://poser.pugx.org/labolagen/laravel-generator/d/daily)](https://packagist.org/packages/labolagen/laravel-generator)
[![License](https://poser.pugx.org/labolagen/laravel-generator/license)](https://packagist.org/packages/labolagen/laravel-generator)

Labolagen - which is short for LAravel-BOilerplate LAravel-GENerator, combines [rappasoft/laravel-boilerplate](https://github.com/rappasoft/laravel-boilerplate) and [InfyOmLabs/laravel-generator](https://github.com/infyomLabs/laravel-generator/) together.

Since laravel-boilerplate is using CoreUI, only the [infyomLabs/coreui-templates](https://github.com/infyomLabs/coreui-templates) is implemented and renamed to [labolagen/coreui-templates](https://github.com/labolagen/coreui-templates).

Usage
==========================

1. Install [rappasoft/laravel-boilerplate](http://laravel-boilerplate.com)
2. `composer require labolagen/laravel-generator:^6.0-dev`
3. `php artisan vendor:publish --provider="Labolagen\Generator\LabolagenGeneratorServiceProvider"`
4. `php artisan labolagen:publish`
5. `php artisan labolagen.publish:layout`
6. Open `app/Providers/RouteServiceProvider.php`, found `mapApiRoutes` function, add `."\\API"` at the end of `$this->namespace`(i.e. `->namespace($this->namespace."\\API")`).
7. Use it as `InfyOmLabs/laravel-generator`.

Because `laravel-boilerplate` is using CoreUI, so I've just implemented `coreui-templates`, it doesn't support `adminlte-templates` or any other templates which InfyOmLabs provided.

If you want to use DataTables in admin panel, do the following:

1. `composer require yajra/laravel-datatables:^1.5`
2. `php artisan vendor:publish --tag=datatables-buttons`
3. Add `<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>` before `@stack('after-scripts')` in `resources/views/backend/layouts/app.blade.php`
4. `npm install --save-dev datatables.net datatables.net-bs4 datatables.net-buttons datatables.net-buttons-bs4`
5. Add the follwing code at the bottom of `resources/js/backend/app.js`:
```javascript
import $ from 'jquery';
import dt from 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-buttons';
import 'datatables.net-buttons-bs4';

window.$ = window.jQuery = $;
```
6. Run `npm run dev` or `yarn dev`

Documentation of laravel-generator is located [here](http://labs.infyom.com/laravelgenerator)

[Video Tutorials](https://github.com/shailesh-ladumor/infyom-laravel-generator-tutorial) (**Credits**: [shailesh-ladumor](https://github.com/shailesh-ladumor))

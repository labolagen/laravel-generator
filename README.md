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
2. `composer require labolagen/laravel-generator:^6.0-develop`
3. Use it as `InfyOmLabs/laravel-generator`.

Because `laravel-boilerplate` is using CoreUI, so I've just implemented `coreui-templates`, it doesn't support `adminlte-templates` or any other templates which InfyOmLabs provided.

One thing more, vuejs templates has not been adjust to use with this package. I will remove it in the near future, so don't use it.

If you want to use DataTables in admin panel, do the following:

1. `composer require yajra/laravel-datatables:^1.5`
2. `php artisan vendor:publish --tag=datatables-buttons`
Documentation is located [here](http://labs.labolagen.com/laravelgenerator)
3. `npm install datatables.net datatables.net-bs4 datatables.net-buttons datatables.net-buttons-bs4`
4. Add the follwing code at the bottom of `resources/js/backend/app.js`:
```javascript
import $ from 'jquery';
import dt from 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-buttons';
import 'datatables.net-buttons-bs4';

window.$ = window.jQuery = $;
```
5. Run `npm run dev` or `yarn dev`


[Video Tutorials](https://github.com/shailesh-ladumor/infyom-laravel-generator-tutorial) (**Credits**: [shailesh-ladumor](https://github.com/shailesh-ladumor))

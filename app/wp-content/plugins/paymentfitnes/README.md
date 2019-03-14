
# paymentfitnes

## Desarrollo frontend

Trabajar los estilos(scss), script, imgs y fonts en la carpeta src y usar los comandos de gulp
* gulp build(compila los archivos de la carpeta src en modo desarrollo)
* gulp (ejecuta la tarea gulp build + server de desarrollo)

```sh
gulp build
gulp

```

Siempre antes de enviar un pull o hacer commit se recomienda ejecutar la tarea de gulp dist(compila para producción)

```sh
gulp dist

```

### Error común en gulp resueltos

Error: watch ENOSPC

```sh
sudo bash -c 'echo 524288 > /proc/sys/fs/inotify/max_user_watches'
```


## Sass utils core
Use sass mixin responsive helpers file in `sass/mixin/_media_queries.scss`

Example use `@include maxw(sm){};` for  `@media (max-width: 767px){};`

Or use `@include minw(sm){};` for  `@media (min-width: 768px){};`

#### Input sass example use mixin mq and style code sass for component

Use BEM for write css: https://css-tricks.com/bem-101/

```scss
.my-component{
    // use example props test
    // props
    &--dark{
        // props
    }
    &__head{
        // props
    }
    &__title{
        // props
    }
    &__body{
        // props
    }
    &__footer{
        // props
    }
    // use max-width
    @include maxw(sm){
        // props
    }
    @include maxw(xs){
        // props
        &__head{
            // props
        }
    }
    // use max-width mq custom
    @include maxw(360px){
        // props
    }
    // use min-width
    @include minw(xs){
        // props
    }
    // use min-width mq custom
    @include minw(360px){
        // props
    }
}
```

#### Output css
```css
.my-component {
    // props
}
.my-component__head {
    // props
}
.my-component__title {
    // props
}
.my-component__body {
    // props
}
.my-component__footer {
    // props
}

@media (min-width: 360px) {
  .my-component {
    // props
  }
}

@media (max-width: 767px) {
    .my-component{
        // props
    }
}
...

// And more props define media queries...

```

#### Existing mixin media queries:
Mixin `maxw($breakpoint)` with parameters **lg** = 1289px , **md** = 1041px , **sm** = 767px , **xs** = 575px.

Example `@include maxw(md){...};`  output  `@media (max-width: 1041px){...};`

Mixin `minw($breakpoint)` with parameters **lg** = 1290px , **md** = 1042px , **sm** = 768px , **xs** = 576px.

Example `@include minw(sm){...};`  output  `@media (max-width: 768px){...};`

## Desarrollo backend
Agregar variables de configuración en `wp-config.php`  
variables necesarias

	define('WP_ENV', 'dev');

## Contributors
@Altimea




# CULQI API (PRODUCCION) DEVELOPMENT

Usar el API REST culqi
Aqui se muestra un ejemplo es posible crear eliminar actualizar pero con sus restricciones del propio API
Por ejempo no puedes actualizar el precio del plan (debes de crear otro si quieres otro precio).

### Eliminar un plan

	DELETE https://api.culqi.com/v2/plans/{id}

### Eliminar con POSTMAN

	DELETE https://api.culqi.com/v2/plans/pln_live_7dDJvkPfp1roBXXX


TAB: Authorization : Type = No Auth
TAB: Headers
	
	# autollenable
	Content-Type	application/x-www-form-urlencoded
	.
	.
	Authorization	Bearer sk_live_FTsYxsurZUIUoXXX

El parametro de autenticacion en la **llave privada** que culqi te proporciona


![DELETE REST CULQI](https://imgur.com/t5bSjuy)
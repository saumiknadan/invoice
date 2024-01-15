<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        @vite('resources/css/app.css')
        <title>Laravel</title>

      
    </head>
   <body>
    <div id="app">
        <app-component>

        </app-component>

        @vite('resources/js/app.js')
    </div>
   </body>
</html>

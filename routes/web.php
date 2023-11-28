<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return Route::getRoutes()->getPath();
// });

Route::get('/', function () {
    $routeCollection = Route::getRoutes();
    print_r(
        array_intersect_key(
          $_SERVER,
          array_flip(
            preg_grep(
              '/^HTTP_/', 
              array_keys($_SERVER),
              0
            )
          )
        )
      );
    echo $_SERVER['REMOTE_ADDR'];
    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        if(str_contains($value->uri(),'api') || $value->uri()=='/'){
            echo "<tr>";
            echo "<td>" . $value->methods()[0] . "</td>";
            echo "<td>" . $value->uri() . "</td>";
            echo "<td>" . $value->getName() . "</td>";
            echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
})->name('route.show');;

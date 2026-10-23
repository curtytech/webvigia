<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/agendamento', function () {
    return view('scheduling.index');
});

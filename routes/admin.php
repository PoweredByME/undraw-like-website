<?php

Route::get('/home', function () {
    $user = Auth::guard('admin')->user();
    return redirect('/admin/home/'.$user->id);
})->name('home');


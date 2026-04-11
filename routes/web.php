<?php

use Illuminate\Support\Facades\Route;

// Page principale Laravel
Route::get("/", function () {
    return redirect("/app");
});

// Manifest PWA
Route::get("/react/manifest.json", function () {
    return response()->file(
        public_path("react/manifest.json"),
        ["Content-Type" => "application/manifest+json"]
    );
});

// Application enfant React PWA
Route::get("/app/{any?}", function () {
    $indexPath = public_path("react/index.html");
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    return redirect("/");
})->where("any", ".*");

// Mode TV
Route::get("/tv/{any?}", function () {
    $indexPath = public_path("react/index.html");
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    return redirect("/");
})->where("any", ".*");

// Espace Mama Judi
Route::get("/mama/{any?}", function () {
    $indexPath = public_path("react/mama.html");
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    return redirect("/app");
})->where("any", ".*");

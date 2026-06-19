<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/tokens/create", function (Request $request) {
    $validated = $request->validate([
        "email" => "required|email",
        "password" => "required",
        "device_name" => "required|string",
    ]);

    $user = \App\Models\User::where("email", $validated["email"])->first();

    if (! $user || ! \Illuminate\Support\Facades\Hash::check($validated["password"], $user->password)) {
        return response()->json(["message" => "Invalid credentials"], 401);
    }

    return ["token" => $user->createToken($validated["device_name"])->plainTextToken];
});

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

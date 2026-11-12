<?php
// Add this inside ->withExceptions(...) in YOUR existing bootstrap/app.php
// (so validation / 404 errors on API routes always return JSON, never a redirect):

->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
    $exceptions->shouldRenderJsonWhen(fn ($request, $e) => $request->is('api/*') || $request->expectsJson());
})

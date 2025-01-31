protected $middleware = [
    // ... existing middleware
    \Illuminate\Http\Middleware\HandleCors::class,
];

protected $middlewareGroups = [
    'api' => [
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Http\Middleware\HandleCors::class,
    ],
];
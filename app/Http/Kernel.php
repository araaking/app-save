protected $middleware = [
    \Illuminate\Http\Middleware\HandleCors::class,
    // ... other middleware
];

protected $middlewareGroups = [
    'api' => [
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Http\Middleware\HandleCors::class,
    ],
];
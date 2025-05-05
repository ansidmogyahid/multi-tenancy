<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::middleware('tenant')->group(function() {
    Route::get('/', function () {
        dd(\Spatie\Multitenancy\Models\Tenant::count());
        $currentTenant = \Spatie\Multitenancy\Models\Tenant::current();
        dd($currentTenant);
        return view('welcome');
    });

    Route::get('/test-db', function () {
        $currentTenant = app('currentTenant');
        $testData = \DB::select('SELECT DATABASE() as current_db');
        
        return [
            'tenant' => $currentTenant ? $currentTenant->name : 'none',
            'expected_db' => $currentTenant ? $currentTenant->database : 'none',
            'actual_db' => $testData[0]->current_db,
            'sample_data' => \DB::table('users')->select('id', 'name')->get(),
        ];
    });
});

Route::get('/debug-tenant', function () {
    $currentTenant = app('currentTenant');
    
    return [
        'accessing_domain' => request()->getHost(),
        'current_tenant_id' => $currentTenant ? $currentTenant->id : null,
        'current_tenant_name' => $currentTenant ? $currentTenant->name : null,
        'current_tenant_domain' => $currentTenant ? $currentTenant->domain : null,
        'current_database' => $currentTenant ? $currentTenant->database : null,
        'connection_name' => DB::connection()->getName(),
        'users' => User::all()
    ];
});
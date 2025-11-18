<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Get current giornata
     */
    public function getCurrentGiornata()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'giornata' => (int) Setting::get('current_giornata', 1)
            ]
        ]);
    }
}

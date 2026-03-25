<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class AppSettingController extends Controller
{
    public function settings()
    {
        $settings = AppSetting::firstOrCreate([]);
        return view('administration.appsettings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'mail_from_address'  => 'nullable|email',
            'mail_from_name'     => 'nullable|string|max:255',
            'mail_host'          => 'nullable|string|max:255',
            'mail_port'          => 'nullable|string|max:10',
            'mail_username'      => 'nullable|string|max:255',
            'mail_password'      => 'nullable|string|max:255',
        ]);

        $settings = AppSetting::firstOrCreate([]);
        $settings->update($validated);

        // Update .env file
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $envKey = strtoupper($key);
                $this->updateEnvVariable($envKey, $value);
            }
        }

        // Log the change
        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }

    private function updateEnvVariable($key, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $pattern = "/^{$key}=.*$/m";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$value}", $content);
            } else {
                $content .= PHP_EOL . "{$key}={$value}";
            }

            file_put_contents($path, $content);
        }
    }
}

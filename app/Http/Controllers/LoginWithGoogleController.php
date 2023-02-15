<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\AccesoGoogle;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class LoginWithGoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->with(['hd' => 'unsa.edu.pe'])->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            //$personal = AccesoGoogle::where('correo', $user->email)->first();
            $personal = User::where('email', $user->email)->first();


            if (!$personal) { // si no es personal de caja
                \Session::flash('errorLoginMessage', "Lo sentimos, esta aplicación es solo para personal registrado en el sistema de posgrado");
                return redirect()->to('/login');
            }

            $finduser = User::where('google_id', $user->id)->first();
            //$finduser = User::where('email', $user->email)->first();

            if ($finduser) {
                Auth::login($finduser);

                return redirect()->intended('dashboard');
            } elseif(User::where('email', $user->email)->first()) {
                $updatedUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,  // verificar si el nombre tomara de google o del admin
                        'email' => $user->email,
                        'google_id' => $user->id,
                        'profile_photo_path' => $user->avatar
                    ]
                );
                Auth::login($updatedUser);

                return redirect()->intended('dashboard');
            }
            else{
                $updatedUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,  // verificar si el nombre tomara de google o del admin
                        'email' => $user->email,
                        'google_id' => $user->id,
                        'password' => bcrypt('administrado2021'),
                        'profile_photo_path' => $user->avatar
                    ]
                );

                /*$newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'profile_photo_path' => $user->avatar,
                ]);*/

                $updatedUser->assignRole('Administrado');
                Auth::login($updatedUser);

                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            Log::warning('LoginWithGoogleController@handleGoogleCallback, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}

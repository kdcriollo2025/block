<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (auth()->user()->type === User::TYPE_ADMIN) {
            return RouteServiceProvider::HOME;
        }
        
        return RouteServiceProvider::HOME_MEDICO;
    }

    protected function authenticated(Request $request, $user)
    {
        Log::info('User authenticated successfully', ['user_id' => $user->id]);
        
        session(['last_activity' => Carbon::now()]);

        // Verificar si es médico y está activo
        if ($user->type === 'medico') {
            $medico = $user->medico;
            if (!$medico || !$medico->is_active) {
                $this->guard()->logout();
                return redirect()->route('login')
                    ->with('error', 'Tu cuenta ha sido desactivada. Por favor, contacta al administrador.');
            }
        }

        // Verificar primer ingreso
        if ($user->first_login) {
            return redirect()->route('password.change.form')
                ->with('warning', 'Por favor, cambia tu contraseña antes de continuar.');
        }

        // Redireccionar según el tipo de usuario
        if ($user->type === 'admin') {
            return redirect('/admin/medicos');
        }

        return redirect('/medico/dashboard');
    }

    protected function loggedOut(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->only('login', 'password');
        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'cedula';

        if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']], $request->filled('remember'))) {
            return redirect()->intended($this->redirectPath());
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        try {
            // Verificar conexión a la base de datos
            DB::connection()->getPdo();
            Log::info('Database connected successfully');
            
            // Intentar login
            $result = $this->guard()->attempt(
                $this->credentials($request), $request->filled('remember')
            );
            
            Log::info('Login attempt result:', ['success' => $result]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return false;
        }
    }
}

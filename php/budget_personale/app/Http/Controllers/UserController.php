<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\UserSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class UserController extends Controller
{
    public function index(): RedirectResponse
    {
        return UserSession::isLoggedIn()
            ? redirect()->route('dashboard')
            : redirect()->route('login.form');
    }

    public function showLoginForm(): View
    {
        return view('user.auth', ['mode' => 'login']);
    }

    public function showRegisterForm(): View
    {
        return view('user.auth', ['mode' => 'register']);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = new User();

        try {
            $id = $user->register($data['name'], $data['email'], $data['password']);
        } catch (InvalidArgumentException $exception) {
            return back()
                ->withErrors(['email' => $exception->getMessage()])
                ->withInput($request->only('name', 'email'));
        }

        UserSession::login([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return redirect()->route('dashboard')->with('status', 'Account creato con successo.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = (new User())->login($credentials['email'], $credentials['password']);

        if ($user === null) {
            return back()
                ->withErrors(['email' => 'Credenziali non valide.'])
                ->withInput($request->only('email'));
        }

        UserSession::login($user);

        return redirect()->route('dashboard')->with('status', 'Accesso effettuato con successo.');
    }

    public function dashboard(): View
    {
        $user = null;

        $userId = UserSession::userId();

        if ($userId !== null) {
            $user = (new User())->findById($userId);
        }

        return view('user.index', [
            'user' => $user ?? [
                'name' => UserSession::userName(),
                'email' => UserSession::userEmail(),
                'created_at' => null,
            ],
        ]);
    }

    public function logout(): RedirectResponse
    {
        UserSession::logout();

        return redirect()->route('login.form')->with('status', 'Logout eseguito.');
    }
}

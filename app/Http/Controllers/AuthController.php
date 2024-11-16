<?php

namespace App\Http\Controllers;

use App\Models\GestionnaireCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Models\Poste;


class AuthController extends Controller
{
    public function show_Login_Admin()
    {
        return view('BackOffice.login');
    }

    public function show_Index_Admin()
    {
        return view('BackOffice.index');
    }

    public function login_Admin(Request $request)
    {
        // Validation des données du formulaire de connexion
        $request->validate([
            'nom' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Recherche de l'utilisateur dans la base de données
        $user = Admin::where('nom', $request['nom'])->first();
    
        // Vérification si l'utilisateur existe et si le mot de passe correspond
        if ($user && Hash::check($request['password'], $user->password)) {
            // L'utilisateur est authentifié avec succès
            Auth::guard('admin')->login($user); // Authentifier l'utilisateur avec le guard admin
            return redirect()->intended('/BackOffice/Index')->with('success', 'Connexion réussie'); // Redirection vers la page de tableau de bord après la connexion réussie
        } else {
            // L'authentification a échoué
            return back()->withErrors([
                'password' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
            ])->withInput(); // Redirection avec erreurs et données précédemment saisies
        }
    }

    public function logout_admin(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/BackOffice/Login');
    }

    //? gestionnaire 
    public function login_Manager(Request $request)
    {
        // Validation des données du formulaire de connexion
        $request->validate([
            'nom' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Recherche de l'utilisateur dans la base de données
        $user = GestionnaireCentre::where('nom_gestionnaire', $request['nom'])->first();
    
        // Vérification si l'utilisateur existe et si le mot de passe correspond
        if ($user && Hash::check($request['password'], $user->password)) {
            // L'utilisateur est authentifié avec succès
            Auth::guard('manager')->login($user); // Authentifier l'utilisateur avec le guard admin
            return redirect()->intended('/FrontOffice/Index')->with('success', 'Connexion réussie'); // Redirection vers la page de tableau de bord après la connexion réussie
        } else {
            // L'authentification a échoué
            return back()->withErrors([
                'password' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
            ])->withInput(); // Redirection avec erreurs et données précédemment saisies
        }
    }
    public function show_manager_registration()
    {
        $postes = Poste::all();
        return view('FrontOffice.register', compact('postes'));
    }

    public function treatment_manager_registration(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed', // Confirme que le champ password_confirmation correspond au champ password
            'id_poste' => 'required|integer'
        ]);

        GestionnaireCentre::create([
            'nom_gestionnaire' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hachage du mot de passe
            'id_poste' => $request->id_poste,
        ]);
        
        return redirect()->route('login.manager')->with('success', 'Votre compte a été créé avec succès. Veuillez vous connecter.');

    }

    public function show_Index_Manager()
    {
        return view('FrontOffice.index');
    }

    public function logout_manager(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\GestionnaireCentre;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        // Valider l'email
        $request->validate(['email' => 'required|email:rfc,dns']);
    
        // Vérifier si l'e-mail existe dans la table GestionnaireCentre
        $gestionnaire = GestionnaireCentre::where('email', $request->email)->first();
    
        if (!$gestionnaire) {
            return back()->withErrors(['email' => 'Cet email ne correspond à aucun compte.']);
        }
    
        // Tenter d'envoyer le lien de réinitialisation
        $status = Password::broker('managers')->sendResetLink(
            $request->only('email')
        );
    
return $status === Password::RESET_LINK_SENT
    ? back()->with(['status' => trans($status)])  // Utilisation de `trans` pour la traduction
    : back()->withErrors(['email' => trans($status)]);

    }
    

    public function showLinkRequestForm()
    {
        return view('FrontOffice.forget_password');  // Votre vue actuelle
    }

}

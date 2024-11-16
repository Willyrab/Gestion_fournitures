<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poste;
use App\Models\Article;
use App\Models\Departement;
use App\Models\ResponsableDepartement;
use App\Models\BesoinsDepartement;
use App\Models\Nouveaubesoins_departement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DepartementController extends Controller
{
    public function show_Login_Departement()
    {
        return view('FrontOffice.Departement.login');
    }

    public function show_Index_Departement()
    {
        return view('FrontOffice.Departement.index');
    }

    public function show_Departement_registration()
    {
        $postes = Poste::all();
        $departements = Departement::all();
        return view('FrontOffice.Departement.register', compact('departements', 'postes'));
    }

    public function treatment_departement_registration(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed', // Confirme que le champ password_confirmation correspond au champ password
            'id_poste' => 'required|integer',
            'id_departement' => 'required|integer'
        ]);

        ResponsableDepartement::create([
            'nom_responsable' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hachage du mot de passe
            'id_poste' => $request->id_poste,
            'id_departement' => $request->id_departement
        ]);

        return redirect()->route('login.departement')->with('success', 'Votre compte a été créé avec succès. Veuillez vous connecter.');

    }

    public function login_departement(Request $request)
    {
        // Validation des données du formulaire de connexion
        $request->validate([
            'nom' => 'required|string',
            'password' => 'required|string',
        ]);

        // Recherche de l'utilisateur dans la base de données
        $user = ResponsableDepartement::where('nom_responsable', $request['nom'])->first();

        // Vérification si l'utilisateur existe et si le mot de passe correspond
        if ($user && Hash::check($request['password'], $user->password)) {
            // L'utilisateur est authentifié avec succès
            Auth::guard('departement')->login($user); // Authentifier l'utilisateur avec le guard admin
            return redirect()->intended('/Departement/Index')->with('success', 'Connexion réussie'); // Redirection vers la page de tableau de bord après la connexion réussie
        } else {
            // L'authentification a échoué
            return back()->withErrors([
                'password' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
            ])->withInput(); // Redirection avec erreurs et données précédemment saisies
        }
    }

    public function logout_departement(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Departement/Login');
    }
    public function demande_article()
    {
        // Récupérer l'ID du responsable connecté
        $responsable = Auth::guard('departement')->user(); // Vérifiez que cela fonctionne pour votre cas
        $id_poste_responsable = $responsable->id_poste; // ID du poste du responsable

        // Récupérer les articles associés à ce poste
        $articles = Article::whereHas('gestionnaire', function ($query) use ($id_poste_responsable) {
            $query->where('id_poste', $id_poste_responsable);
        })->get();

        return view('FrontOffice.Departement.demande_article', compact('articles'));
    }

    public function traitementdemande_article(Request $request)
    {

        $id_responsable = Auth::guard('departement')->id();
        $request->validate([
            'id_article' => 'required|exists:articles,id_article',
            'quantite' => 'required|integer|min:1'
        ]);

        BesoinsDepartement::create([
            'id_article' => $request->id_article, // Retirer le second $ ici
            'quantite' => $request->quantite,
            'id_responsable_departement' => $id_responsable,
        ]);

        return redirect()->back()->with('success', 'Demande d\'article envoyée avec succès.');
    }

    public function demander_nouveau_article()
    {

        return view('FrontOffice.Departement.nouveau_article');

    }
    public function traitementdemande_nouveau_article(Request $request)
    {

        $id_responsable = Auth::guard('departement')->id();
        $request->validate([
            'nom_article' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
            'unite' => 'required|string|max:50',
            'motifs' => 'required',
        ]);

        Nouveaubesoins_departement::create([
            'id_responsable_departement' => $id_responsable,
            'nom_article' => $request->nom_article,
            'description' => $request->description,
            'quantite' => $request->quantite,
            'unite' => $request->unite,
            'motif_demande' => $request->motifs,

        ]);

        return redirect()->back()->with('success', 'Demande d\'article envoyée avec succès.');
    }


    public function lister_NDarticle(Request $request)
    {
        // Récupérer l'ID du responsable connecté
        $id_responsable = Auth::guard('departement')->id();
        $id_nouveaubesoins = $request->input('nom_article');

        // Récupérer les demandes d'articles du responsable
        $demandes = Nouveaubesoins_departement::with('status') // Assurez-vous que la relation 'status' soit définie dans votre modèle
            ->where('id_responsable_departement', $id_responsable);

        // Filtrer par date de demande si spécifié
        if ($request->has('date_demande') && $request->date_demande) {
            $demandes->whereDate('date_demande', $request->date_demande);
        }

        // Filtrer par nom d'article si spécifié
        if ($request->has('nom_article') && $request->nom_article) {
            $nomArticle = trim($request->nom_article); // Supprime les espaces avant et après
            $demandes->whereRaw('LOWER(nom_article) LIKE ?', ['%' . strtolower($nomArticle) . '%']);
        }


        // Pagination et ajout des paramètres de requête à l'URL
        $demandes = $demandes->paginate(10)->appends([
            'nom_article' => $request->input('nom_article'), // Conserve la valeur de la recherche
            'date_demande' => $request->input('date_demande'), // Conserve la date si elle est entrée
        ]);

        return view('FrontOffice.Departement.listedemande_NouveauArtcile', compact('demandes'));
    }

    // public function listerdemande_article(Request $request)
    // {
    //     // Récupérer l'ID du responsable connecté
    //     $id_responsable = Auth::guard('departement')->id();

    //     // Récupérer les demandes d'articles du responsable
    //     $demandes = BesoinsDepartement::with(['article', 'status'])
    //         ->where('id_responsable_departement', $id_responsable);

    //     // Filtrer par date de demande si spécifié
    //     if ($request->has('date_demande') && $request->date_demande) {
    //         $demandes->whereDate('date_demande', $request->date_demande);
    //     }

    //     // Filtrer par nom d'article si spécifié
    //     if ($request->has('nom_article') && $request->nom_article) {
    //         $nomArticle = trim($request->nom_article); // Supprime les espaces
    //         $demandes->whereHas('article', function ($query) use ($nomArticle) {
    //             $query->whereRaw('LOWER(nom_article) LIKE ?', ['%' . strtolower($nomArticle) . '%']);
    //         });
    //     }

    //     $demandes = $demandes->paginate(10)->appends([
    //         'nom_article' => $request->input('nom_article'), // Conserve la valeur de la recherche
    //         'date_demande' => $request->input('date_demande'), // Conserve la date si elle est entrée
    //     ]);

    //     return view('FrontOffice.Departement.listedemande_article', compact('demandes'));
    // }

    public function listerdemande_article(Request $request)
{
    // Récupérer l'ID du responsable connecté
    $id_responsable = Auth::guard('departement')->id();

    // Récupérer les demandes d'articles du responsable
    $demandes = BesoinsDepartement::with(['article', 'status'])
        ->where('id_responsable_departement', $id_responsable);

    // Filtrer par date de demande si spécifié
    if ($request->has('date_demande') && $request->date_demande) {
        $demandes->whereDate('date_demande', $request->date_demande);
    }

    // Filtrer par statut si spécifié
    if ($request->has('status') && $request->status) {
        $demandes->where('id_status', $request->status);
    }

    // Paginer les résultats en conservant les valeurs de recherche
    $demandes = $demandes->paginate(10)->appends([
        'date_demande' => $request->input('date_demande'),
        'status' => $request->input('status'),
    ]);

    return view('FrontOffice.Departement.listedemande_article', compact('demandes'));
} 
    public function updateDemandeArticle(Request $request, $id)
{
    // Récupérer l'article par son ID

    $article = Nouveaubesoins_departement::findOrFail($id);

      // Vérifier si le statut est validé
      if ($article->id_status == 2 || $article->id_status == 4) {
        return redirect()->route('liste_NDarticle.departement')->with('error', 'La demande ne peut pas être modifiée car elle a déjà validée.');
    }

    // Validation des données reçues
    $request->validate([
        'nom_article' => 'required|string|max:255',
        'description' => 'nullable|string',
        'quantite' => 'required|integer|min:1', 
        'unite' => 'required|string|max:50',
        'motifs' => 'nullable|string|max:255', 
    ]);

    // Mettre à jour les propriétés de l'article
    $article->nom_article = $request->nom_article;
    $article->description = $request->description;
    $article->quantite = $request->quantite;
    $article->unite = $request->unite;
    $article->motif_demande = $request->motifs; 
    $article->save();

    return redirect()->route('liste_NDarticle.departement')->with('success', 'Demande d\'article modifiée avec succès');
}

}

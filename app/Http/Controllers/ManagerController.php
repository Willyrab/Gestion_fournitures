<?php

namespace App\Http\Controllers;
use App\Models\DemandeAchat;
use Illuminate\Http\Request;
use App\Models\GestionnaireCentre;
use App\Models\Categorie;
use App\Models\Article;
use App\Models\Fournisseurs;
use App\Models\BesoinsDepartement;
use App\Models\V_responsables_departements;
use App\Models\V_demande_achat;
use App\Models\V_besoins_departement;
use App\Models\V_nouveaubesoins_article;
use Illuminate\Support\Facades\Auth;


class ManagerController extends Controller
{
    //? formulaire insert article
    public function show_Insert_Article()
    {
        $categories = Categorie::all();
        return view('FrontOffice.insert_article', compact('categories'));
    }
    public function Form_EntreeStock_Article()
    {
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Filtrer les articles et les fournisseurs en fonction de cet id_gc
        $articles = Article::where('id_gc', $id_gc)->get();
        $fournisseurs = Fournisseurs::where('id_gc', $id_gc)->get();

        // Passer les données filtrées à la vue
        return view('FrontOffice.entree_article', compact('articles', 'fournisseurs'));
    }
    public function Form_SortieStock_Article()
    {
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Filtrer les articles et les fournisseurs en fonction de cet id_gc
        $articles = Article::where('id_gc', $id_gc)->get();
        // Récupérer le poste du gestionnaire connecté
        $gestionnaire = GestionnaireCentre::find($id_gc);
        $id_poste = $gestionnaire->id_poste;

        // Récupérer les départements dont les responsables ont le même poste que le gestionnaire connecté
        $departements = V_responsables_departements::getDepartementsByPoste($id_poste);

        // Passer les données filtrées à la vue
        return view('FrontOffice.sortie_article', compact('articles', 'departements'));
    }

    //? liste DA New article
    // public function Demande_Article_Admin()
    // {
    //     // Récupérer l'id_poste du gestionnaire connecté
    //     $id_poste = Auth::guard('manager')->user()->id_poste;
    
    //     // Récupérer les demandes de besoins du même poste que le gestionnaire connecté
    //     $articles = Nouveaubesoins_departement::with('status', 'demandeTransferee') // Charger les relations nécessaires
    //         ->whereHas('responsableDepartement', function ($query) use ($id_poste) {
    //             $query->where('id_poste', $id_poste);
    //         })
    //         ->get();
    
    //     // Passer les données à la vue
    //     return view('FrontOffice.demandeAchatAdmin', compact('articles'));
    // }
    public function Demande_Article_Admin()
{
    // Récupérer l'id_poste du gestionnaire connecté
    $id_poste = Auth::guard('manager')->user()->id_poste;

    // Récupérer les demandes de besoins du même poste que le gestionnaire connecté
    $articles = V_nouveaubesoins_article::where('id_poste', $id_poste)->get();

    // Passer les données à la vue
    return view('FrontOffice.demandeAchatAdmin', compact('articles'));
}

    public function sendDemande_achat(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'id_nouveaubesoins.*' => 'required|exists:nouveaubesoins_departement,id_nouveaubesoins',
            'quantite.*' => 'required|integer|min:1',
        ]);
    
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();
    
        // Boucler sur chaque besoin d'article demandé
        foreach ($request->id_nouveaubesoins as $index => $id_nouveaubesoins) {
            DemandeAchat::create([
                'id_nouveaubesoins' => $id_nouveaubesoins,
                'id_gc' => $id_gc,
                'id_status' => 1, // Statut par défaut
                'date_demande' => now(), // Date de la demande
                'quantite' => $request->quantite[$index] // Utiliser l'index pour accéder à la quantité correspondante
            ]);
        }
    
        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Demande d\'achat envoyée avec succès.');
    }
    

    public function afficherdemande_achat(Request $request)
    {
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();
        // Récupérer l'historique des entrées pour ce gestionnaire
        $query = V_demande_achat::where('id_gc', $id_gc);

                // Filtrer par nom d'article si spécifié
                if ($request->filled('nom_article')) {
                    $nom_article = trim($request->input('nom_article')); // Utiliser trim()
                    $query->whereRaw('LOWER(nom_article) LIKE ?', ['%' . strtolower($nom_article) . '%']);
                }
            
                // Filtrer par date de demande si spécifié
                if ($request->filled('date_envoi')) {
                    $query->whereDate('date_envoi', $request->date_envoi);
                }

                $demandeachats  = $query->paginate(5)->appends($request->all());
        // Passer les données à la vue
        return view('FrontOffice.listdemandeachat', compact('demandeachats'));
    }
    public function updateDA(Request $request, $id)
    {
        $demande = DemandeAchat::findOrFail($id);

        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $demande->quantite = $request->quantite;
        $demande->save();

        return redirect()->route('list.demandeachat')->with('success', 'Demande Achat modifié avec succès');
    }
    public function listerDemandeArticle(Request $request)
    {
        // Récupérer le gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();
    
        // Récupérer le poste de ce gestionnaire
        $gestionnaire = GestionnaireCentre::findOrFail($id_gc);
        $id_poste = $gestionnaire->id_poste; 
    
        // Récupérer la liste des départements associés à ce poste (distinct)
        $departements = V_besoins_departement::where('id_poste', $id_poste)
            ->distinct('id_departement')
            ->get(['id_departement', 'nom_departement']);
    
        // Récupérer les demandes d'articles ayant le même id_poste
        $demandes = V_besoins_departement::where('id_poste', $id_poste)
            ->orderBy('date_demande', 'DESC');
    
        // Filtrer par date de demande si spécifié
        if ($request->has('date_demande') && $request->date_demande) {
            $demandes->whereDate('date_demande', $request->date_demande);
        }
    
        // Filtrer par poste si spécifié
        if ($request->has('departement') && $request->departement) {
            $demandes->where('id_departement', $request->departement); 
        }
    
        // Paginer les résultats
        $demandes = $demandes->paginate(10)->appends($request->all());
    
        return view('FrontOffice.listedemande_article', compact('demandes', 'departements'));
    }
    
    public function validerDemandeArticle($id_besoins)
    {
        // Récupérer la demande d'approvisionnement par son ID
        $demande = BesoinsDepartement::findOrFail($id_besoins);

        // Vérifier si la demande est en attente
        if ($demande->id_status === 1) { // Si le statut est "En attente"
            // Mettre à jour le statut à "Validé" (par exemple, id_status = 2 pour "Validé")
            $demande->id_status = 2;
            $demande->save();
        }
        // Rediriger avec un message de succès
        return redirect()->route('list.besoinsDepartement')->with('success', 'La demande a été validée avec succès.');
    }


    public function lister_nouveau_besoins(Request $request)
    {
        $id_gc = Auth::guard('manager')->id();
        // Récupérer le poste de ce gestionnaire
        $gestionnaire = GestionnaireCentre::findOrFail($id_gc);
        $id_poste = $gestionnaire->id_poste; // Assurez-vous que ce champ existe

        // Récupérer les demandes de nouveaux articles en fonction du poste
        $demandes = V_nouveaubesoins_article::where('id_poste', $id_poste)
            ->orderBy('date_demande', 'DESC');

        // Filtrer par date de demande si spécifié
        if ($request->has('date_demande') && $request->date_demande) {
            $demandes->whereDate('date_demande', $request->date_demande);
        }
        // Pagination avec ajout des paramètres de recherche
        $demandes = $demandes->paginate(10)->appends($request->all());

        // Passer les données à la vue
        return view('FrontOffice.list_demandenouveauarticle', compact('demandes'));
    }
    // public function transfererDemande(Request $request, $id_nouveaubesoins)
    // {
    //     // Récupérer la demande de nouveau besoin
    //     $demande = Nouveaubesoins_departement::findOrFail($id_nouveaubesoins);

    //     // Vérifier si la demande est en attente
    //     if ($demande->id_status === 1) { // Assurez-vous que l'id_status 1 correspond à "En attente"
    //         // Créer un nouvel enregistrement dans la table demande_transferer
    //         $demandeTransferee = new DemandeTransferee();
    //         $demandeTransferee->id_nouveaubesoins = $demande->id_nouveaubesoins;
    //         // Changer à l'id_status pour "Transférée"
    //         $demandeTransferee->save();

    //         // Mettre à jour le statut de la demande dans la table nouveaubesoins_departement
    //         $demande->id_status = 4; // Changer à l'id_status pour "Transférée"
    //         $demande->save();

    //         return redirect()->route('demande.nouveauarticle')->with('success', 'Demande transférée avec succès.');
    //     }

    //     return redirect()->route('demande.nouveauarticle')->with('error', 'La demande ne peut pas être transférée car elle n\'est pas en attente.');
    // }

    public function forget_password()
    {
        return view('FrontOffice.forget_password');

    }
    
}

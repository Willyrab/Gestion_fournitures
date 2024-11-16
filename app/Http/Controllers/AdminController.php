<?php

namespace App\Http\Controllers;

use App\Models\Fournisseurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GestionnaireCentre;
use App\Models\V_demande_appro;
use App\Models\V_demande_achat;
use App\Models\V_demande_devis;
use App\Models\V_fournisseur_articles;
use App\Models\V_achatfournisseur;
use App\Models\V_demande_transferer;
use App\Models\Poste;
use App\Models\DemandeAchat;
use App\Models\Article;
use App\Models\AchatFournisseur;
use App\Models\Demande_approvisionnement;
use App\Models\Departement;
use App\Models\Nouveaubesoins_departement;
use DB;
use App\Models\DemandeDevis;
use PDF;



class AdminController extends Controller
{
    public function list_gestionnaire_centre(Request $request)
    {
        // Récupérer l'ID du poste sélectionné
        $selectedPosteId = $request->input('poste');

        // Récupérer tous les postes
        $postes = Poste::all();

        // Récupérer les gestionnaires selon le poste sélectionné, ou tous les gestionnaires si aucun poste n'est sélectionné
        $gestionnaires = GestionnaireCentre::with('poste')
            ->when($selectedPosteId, function ($query, $selectedPosteId) {
                return $query->where('id_poste', $selectedPosteId);
            })
            ->get();

        // Passer les données à la vue
        return view('BackOffice.manager', compact('gestionnaires', 'postes', 'selectedPosteId'));
    }

    public function approvisionnement_avalider(Request $request)
    {
        // Récupérer le poste sélectionné
        $poste_id = $request->input('poste');
        // Créer une requête de base
        $query = V_demande_appro::query();
        // Filtrer par poste si un poste est sélectionné
        if ($poste_id) {
            $query->where('id_poste', $poste_id);
        }
        // Récupérer les demandes paginées
        $approvisionnementavalider = $query->paginate(5)->appends(['poste' => $poste_id]);

        // Récupérer les postes pour le select
        $postes = Poste::all();
        // Passer les données à la vue
        return view('BackOffice.approvisionnementavalider', compact('approvisionnementavalider', 'postes'));
    }

    public function validerDemande($id_demandeapp)
    {
        // Récupérer la demande d'approvisionnement par son ID
        $demande = Demande_approvisionnement::findOrFail($id_demandeapp);

        // Vérifier si la demande est en attente
        if ($demande->id_status === 1) { // Si le statut est "En attente"
            // Mettre à jour le statut à "Validé" (par exemple, id_status = 2 pour "Validé")
            $demande->id_status = 2;
            $demande->save();
        }
        // Rediriger avec un message de succès
        return redirect()->route('demande.approvisionnement.admin')->with('success', 'La demande a été validée avec succès.');
    }


    public function lister_demandeAchat(Request $request)
    {
        // Récupérer le poste sélectionné
        $poste_id = $request->input('poste');

        // Créer une requête de base
        $query = V_demande_achat::query();

        // Filtrer par poste si un poste est sélectionné
        if ($poste_id) {
            $query->where('id_poste', $poste_id);
        }

        // Récupérer les demandes paginées
        $demandeAchats = $query->paginate(5)->appends(['poste' => $poste_id]);

        // Récupérer les postes pour le select
        $postes = Poste::all();

        // Passer les données à la vue
        return view('BackOffice.list_demandeAchat', compact('demandeAchats', 'postes'));
    }

    //? valider nouveau demande 
    // public function validerDemandeAchat($id_demande)
    // {
    //     // Récupérer la demande d'approvisionnement par son ID
    //     $demande = DemandeAchat::findOrFail($id_demande);

    //     // Vérifier si la demande est en attente
    //     if ($demande->id_status === 1) { // Si le statut est "En attente"
    //         // Mettre à jour le statut à "Validé" (par exemple, id_status = 2 pour "Validé")
    //         $demande->id_status = 2;
    //         $demande->save();
    //     }
    //     // Rediriger avec un message de succès
    //     return redirect()->route('demande.Achat.admin')->with('success', 'La demande a été validée avec succès.');
    // }

    //? DA
    public function validerDemandeAchat($id_demande)
    {
        // Récupérer la demande d'achat par son ID
        $demande = DemandeAchat::findOrFail($id_demande);

        // Vérifier si la demande est en attente
        if ($demande->id_status === 1) { // Si le statut est "En attente"
            // Mettre à jour le statut de la demande d'achat à "Validé" (par exemple, id_status = 2 pour "Validé")
            $demande->id_status = 2;
            $demande->save();

            // Mettre à jour le statut de la demande dans nouveaubesoins_departement
            $nouveaubesoins = Nouveaubesoins_departement::find($demande->id_nouveaubesoins);

            if ($nouveaubesoins) {
                $nouveaubesoins->id_status = 2; // Par exemple, 2 pour "Validé"
                $nouveaubesoins->save();
            }
        }

        // Rediriger avec un message de succès
        return redirect()->route('demande.Achat.admin')->with('success', 'La demande et le besoin associé ont été validés avec succès.');
    }


    public function lister_FournisseurArticle(Request $request)
    {
        // Récupérer le poste et l'article sélectionnés
        $poste_id = $request->input('poste');
        $article_nom = trim($request->input('article'));

        // Créer une requête de base
        $query = V_fournisseur_articles::query();

        // Filtrer par poste si un poste est sélectionné
        if ($poste_id) {
            $query->where('id_poste', $poste_id);
        }

        // Filtrer par nom d'article si un nom est fourni
        if ($article_nom) {
            $query->where(DB::raw('LOWER(nom_article)'), 'LIKE', '%' . strtolower($article_nom) . '%');
        }

        // Récupérer les résultats paginés et conserver les paramètres de recherche
        $FounisseurArticles = $query->paginate(6)->appends(['poste' => $poste_id, 'article' => $article_nom]);
        // Récupérer les postes pour le select
        $postes = Poste::all();
        // Passer les données à la vue
        return view('BackOffice.FounisseurArticles', compact('FounisseurArticles', 'postes', 'poste_id', 'article_nom'));
    }
    public function Achats_Fournisseur()
    {
        $postes = Poste::all();
        $fournisseurs = Fournisseurs::all();
        $articles = Article::all();
        $departements = Departement::all();

        return view('BackOffice.achatsfournisseur', compact('postes', 'fournisseurs', 'articles', 'departements'));
    }
    public function insert_achat(Request $request)
    {
        // Validation des données
        $request->validate([
            'id_article' => 'required|array',
            'id_article.*' => 'required|integer', // Chaque article est un ID
            'quantite' => 'required|array',
            'quantite.*' => 'required|integer|min:1', // Quantité doit être un entier
            'prix' => 'required|array',
            'prix.*' => 'required|numeric|min:0', // Prix doit être un nombre positif
            'fournisseur' => 'required|integer', // ID du fournisseur
            'nom_demandeur' => 'required|string|max:100', // Nom de l'acheteur
            'email_demandeur' => 'required|email|max:100', // Email de l'acheteur
            'contact_demandeur' => 'required|string|max:15', // Contact de l'acheteur
            'id_poste' => 'required|integer', // ID du poste
            'lieu_livraison' => 'required|string|max:255',
            'date_livraison' => 'required|date',
            'condition_paiement' => 'required|string|max:255',
        ]);
        //dd($request->all()) ;
        // Génération de la référence unique
        $nextNumber = DB::select("SELECT nextval('achat_fournisseur_reference_seq') as seq")[0]->seq;
        $reference = 'REF-A' . now()->format('Ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        try {
            for ($i = 0; $i < count($request->id_article); $i++) {
                AchatFournisseur::create([
                    'id_article' => $request->id_article[$i],
                    'quantite' => $request->quantite[$i],
                    'prix' => $request->prix[$i],
                    'nom_acheteur' => $request->nom_demandeur,
                    'email_acheteur' => $request->email_demandeur,
                    'contact_acheteur' => $request->contact_demandeur,
                    'id_fournisseur' => $request->fournisseur,
                    'id_poste' => $request->id_poste,
                    'reference' => $reference,
                    'id_status' => 1,
                    'lieu_livraison' => $request->lieu_livraison,
                    'condition_paiement' => $request->condition_paiement,
                    'date_livraison' => $request->date_livraison,
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Achat enregistré avec succès!');
    }

    public function afficherbon_commande(Request $request)
    {
        // Récupérer les valeurs des filtres depuis la requête
        $poste_id = $request->input('poste');
        $date_achat = $request->input('date_achat');  // Supprimer l'espace après 'nom_article'
        $status_id = $request->input('status');
        // Récupérer tous les postes pour le formulaire
        $postes = Poste::all();


        // Créer une requête avec des filtres conditionnels
        $achats = V_achatfournisseur::when($poste_id, function ($query, $poste_id) {
            return $query->where('id_poste', $poste_id);
        })
            ->when($date_achat, function ($query, $date_achat) {
                // Utiliser une comparaison exacte de la date
                return $query->whereDate('date_achat', $date_achat);
            })
            ->when($status_id, function ($query, $status_id) {
                return $query->where('id_status', $status_id);
            })

            ->paginate(5)
            ->appends([
                'poste' => $poste_id,
                'date_achat' => $date_achat,
                'status' => $status_id
            ]);

        // Retourner la vue avec les données
        return view('BackOffice.listbondecommande', compact('achats', 'postes'));
    }

    public function generateBonCommandePDF($id_achat)
    {
        // Rechercher l'achat correspondant à l'ID sélectionné (id_achat)
        $achat = V_achatfournisseur::where('id_achat', $id_achat)->first();

        // Si aucun achat trouvé, rediriger avec un message d'erreur
        if (!$achat) {
            return redirect()->back()->with('error', 'Demande de devis non trouvée');
        }

        // Récupérer tous les articles ayant la même référence_achat
        $articles = V_achatfournisseur::where('reference_achat', $achat->reference_achat)->get();

        // Préparer les informations de l'acheteur
        $acheteur = [
            'nom' => $achat->nom_acheteur,
            'poste' => $achat->poste_achat,
            'entreprise' => 'Huillerie Industrielle de Tamatave',
            'telephone' => $achat->contact_acheteur,
            'email' => $achat->email_acheteur,
            'departement' => $achat->nom_departement
        ];

        // Préparer les informations du fournisseur
        $fournisseurs = [
            'nom_fournisseur' => $achat->nom_fournisseur,
            'lieu' => $achat->lieu_fournisseur,
            'telephone' => $achat->contact_fournisseur,
            'email_fournisseur' => $achat->email_fournisseur,
        ];

        $reference = $achat->reference_achat;
        $date = $achat->date_achat;
        $date_livraison = $achat->date_livraison;
        $lieu_livraison = $achat->lieu_livraison;
        $condition_paiement = $achat->condition_paiement;

        // Générer le PDF en utilisant la vue Blade spécifiée
        try {
            $pdf = PDF::loadView('BackOffice.pdf_bondecommande', compact(
                'articles',
                'acheteur',
                'fournisseurs',
                'reference',
                'date',
                'date_livraison',
                'lieu_livraison',
                'condition_paiement'
            ))->setPaper('a4', 'portrait');

            // Télécharger ou afficher le fichier PDF
               return $pdf->stream('bon_de_commande_' . $reference . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }


    public function destroyBC($id)
    {
        $demande = AchatFournisseur::findOrFail($id);
        $demande->delete();
        return redirect()->route('achat.list.bc')->with('success', 'Demande supprimée avec succès.');
    }

    //? valider achat bon de commande
    public function updateBcstatus($id_achat)
    {
        // Récupérer la demande d'approvisionnement par son ID
        $demande = AchatFournisseur::findOrFail($id_achat);
    
        // Vérifier si la demande est en statut "En attente" (id_status = 1)
        if ($demande->id_status === 1) {
            // Récupérer la référence de la demande sélectionnée
            $reference = $demande->reference;
    
            // Mettre à jour toutes les demandes ayant la même référence
            AchatFournisseur::where('reference', $reference)
                ->where('id_status', 1) // S'assurer qu'on met à jour uniquement celles en "En attente"
                ->update(['id_status' => 5]); // Changer le statut à "Livrée" (id_status = 5)
    
            return redirect()->route('achat.list.bc')->with('success', 'Toutes les demandes avec la même référence ont été validées avec succès.');
        }
    
        return redirect()->route('achat.list.bc')->with('error', 'Cette demande est déjà validée ou ne peut pas être validée.');
    }
    

    //? update BC
    public function updateBC(Request $request, $id)
    {
        $Boncommande = AchatFournisseur::findOrFail($id);

        // Vérifier si le statut est validé
        if ($Boncommande->id_status == 5) {
            return redirect()->route('achat.list.bc')->with('error', 'La demande ne peut pas être modifiée car elle a déjà livrée(s).');
        }

        $request->validate([
            'quantite' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'date_livraison' => 'required',
            'lieu_livraison' => 'required|string',
            'condition_paiement' => 'required|string'
        ]);

        // Mise à jour des propriétés de l'article
        $Boncommande->quantite = $request->quantite;
        $Boncommande->prix = $request->prix;
        $Boncommande ->date_livraison = $request->date_livraison;
        $Boncommande ->lieu_livraison = $request->lieu_livraison;
        $Boncommande ->condition_paiement = $request->condition_paiement;



        $Boncommande->save();

        return redirect()->route('achat.list.bc')->with('success', 'Article modifié avec succès');
    }
    // Méthode pour obtenir la référence de l'article à partir de l'ID de l'article
    private function getReferenceByArticle($id_article)
    {
        $article = Article::find($id_article);
        return $article ? $article->reference_article : null;
    }

    private function getArticleIdByReference($reference)
    {
        // Logique pour obtenir l'ID de l'article à partir de la référence
        $article = Article::where('reference', $reference)->first();
        return $article ? $article->id_article : null;
    }

    public function Demander_devis()
    {

        $postes = Poste::all();
        return view('BackOffice.demandedevis', compact('postes'));

    }
    public function sendDevis(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom_article.*' => 'required|string|max:100',
            'quantite.*' => 'required|integer',
            'unite.*' => 'required|string|max:50',
            'description.*' => 'required|string|max:255',
            'nom_fournisseur' => 'required|string|max:100',
            'email_fournisseur' => 'required|email|max:150',
            'contact_fournisseur' => 'required|string|max:15',
            'lieu_fournisseur' => 'required|string|max:100',
            'nom_demandeur' => 'required|string|max:100',
            'email_demandeur' => 'required|email|max:100',
            'contact_demandeur' => 'required|string|max:15',
            'id_poste' => 'required|integer',
            'date_limite' => 'required|date',
        ]);

        // Obtenir le prochain numéro de la séquence pour générer la référence
        $nextNumber = DB::select("SELECT nextval('demande_devis_reference_seq') as seq")[0]->seq;

        // Générer la référence unique
        $reference = 'REF' . now()->format('Ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Boucle sur chaque article soumis
        foreach ($request->nom_article as $index => $nom_article) {
            // Créer une nouvelle demande de devis pour chaque article
            DemandeDevis::create([
                'nom_article' => $nom_article,
                'quantite' => $request->quantite[$index],
                'unite' => $request->unite[$index],
                'description' => $request->description[$index],
                'nom_fournisseur' => $request->nom_fournisseur,
                'email_fournisseur' => $request->email_fournisseur,
                'contact' => $request->contact_fournisseur,
                'lieu_fournisseur' => $request->lieu_fournisseur,
                'nom_acheteur' => $request->nom_demandeur,
                'email_acheteur' => $request->email_demandeur,
                'contact_acheteur' => $request->contact_demandeur,
                'id_poste' => $request->id_poste,
                'date_limite' => $request->date_limite,
                'id_status' => 1, // ou tout autre statut par défaut
                'reference' => $reference // Référence générée
            ]);
        }

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Demande de devis envoyée avec succès.');
    }
    public function afficherdemande_devis(Request $request)
    {
        // Récupérer les valeurs des filtres depuis la requête
        $poste_id = $request->input('poste');
        $nom_article = trim($request->input('nom_article'));  // Supprimer l'espace après 'nom_article'
        $status_id = $request->input('status');
        // Récupérer tous les postes pour le formulaire
        $postes = Poste::all();

        // Créer une requête avec des filtres conditionnels
        $demandedevis = V_demande_devis::when($poste_id, function ($query, $poste_id) {
            return $query->where('id_poste', $poste_id);
        })
            ->when($nom_article, function ($query, $nom_article) {
                // Utiliser LOWER() sur le champ 'nom_article' et sur la valeur de recherche
                return $query->whereRaw('LOWER(nom_article) LIKE ?', ['%' . strtolower($nom_article) . '%']);
            })
            ->when($status_id, function ($query, $status_id) {
                return $query->where('id_status', $status_id);
            })

            ->paginate(5)
            ->appends([
                'poste' => $poste_id,
                'nom_article' => $nom_article,
                'status' => $status_id
            ]); // Supprimer l'espace dans appends

        // Retourner la vue avec les données
        return view('BackOffice.listdemandedevis', compact('demandedevis', 'postes'));
    }

    public function generateDevisPDF($id_demande)
    {
        // Récupérer la demande de devis par ID
        $demandeDevis = V_demande_devis::where('id_demande', $id_demande)->first();

        if (!$demandeDevis) {
            return redirect()->back()->with('error', 'Demande de devis non trouvée');
        }

        // Récupérer tous les articles liés à la même référence
        $articles = V_demande_devis::where('reference', $demandeDevis->reference)->get();

        $acheteur = [
            'nom' => $demandeDevis->nom_acheteur,
            'poste' => $demandeDevis->nom_poste,
            'entreprise' => 'Huillerie Industrielle de Tamatave',
            'telephone' => $demandeDevis->contact_acheteur,
            'email' => $demandeDevis->email_acheteur,
        ];

        $reference = $demandeDevis->reference;
        $date = $demandeDevis->date_demande;
        $date_fin = $demandeDevis->date_limite;
        $site = 'Site Web';
        $email = $demandeDevis->email_acheteur;

        // Générer le PDF
        try {
            $pdf = PDF::loadView('BackOffice.pdf_demandedevis', compact('articles', 'acheteur', 'reference', 'date', 'site', 'email', 'date_fin'))
                ->setPaper('a4', 'portrait');

            // Télécharger le fichier PDF
            return $pdf->stream('demande_devis_' . $reference . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }
    public function destroyDevis($id)
    {
        $demande = DemandeDevis::findOrFail($id);
        $demande->delete();
        return redirect()->route('list.devis')->with('success', 'Demande supprimée avec succès.');
    }
    public function updateDemandeDevis(Request $request, $id_demande)
    {
        $demande = DemandeDevis::findOrFail($id_demande);

        // Vérifier si le statut est validé
        if ($demande->id_status == 2) {
            return redirect()->route('list.devis')->with('error', 'La demande ne peut pas être modifiée car elle a déjà validée.');
        }
        // Valider les champs modifiables
        $validatedData = $request->validate([
            'nom_article' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'quantite' => 'required|integer',
            'unite' => 'required|string|max:50',
            'date_limite' => 'required|date',
            'nom_fournisseur' => 'required|string|max:100',
            'lieu_fournisseur' => 'required|string|max:100',
            'email_fournisseur' => 'required|email|max:150',
            'contact' => 'required|string|max:15',
            'nom_acheteur' => 'required|string|max:100',
            'email_acheteur' => 'required|email|max:100',
            'contact_acheteur' => 'required|string|max:15',
        ]);

        // Trouver la demande par ID et mettre à jour les champs modifiables

        $demande->update($validatedData);

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Demande de devis mise à jour avec succès.');
    }

    //? valider achat bon de commande
    public function updateDevistatus($id_demande)
    {
        // Récupérer la demande d'approvisionnement par son ID
        $demande = DemandeDevis::findOrFail($id_demande);

        // Vérifier si la demande est en attente
        if ($demande->id_status === 1) { // Si le statut est "En attente"
            $demande->id_status = 2; // Changer le statut à "Validé"
            $demande->save();

            // Rediriger avec un message de succès
            return redirect()->route('list.devis')->with('success', 'La demande a été validée avec succès.');
        }

        // Si la demande n'était pas en attente
        return redirect()->route('list.devis')->with('error', 'Cette demande est déjà validée ou ne peut pas être validée.');
    }



    //? ty le nisy transfer
    // public function lister_nouveau_besoins(Request $request)
    // {
    //     // Récupérer tous les postes pour le formulaire de filtre
    //     $postes = Poste::all();

    //     // Créer la requête de base pour récupérer les demandes de nouveaux articles
    //     $demandes = V_demande_transferer::query();

    //     // Filtrer par poste si spécifié
    //     if ($request->has('id_poste') && $request->id_poste) {
    //         $demandes->where('id_poste', $request->id_poste);
    //     }

    //     // Filtrer par date de demande si spécifié
    //     if ($request->has('date_envoi') && $request->date_demande) {
    //         $demandes->whereDate('date_envoi', $request->date_demande);
    //     }

    //     // Récupérer les demandes triées par date (descendant)
    //     $demandes = $demandes->orderBy('date_envoi', 'DESC')->paginate(10)->appends($request->all());

    //     // Passer les données à la vue
    //     return view('BackOffice.demande_NouveauArtcile', compact('demandes', 'postes'));
    // }


    //? appouver demande nouveua articles transgere
    // public function approuver_NDarticle($id_nouveaubesoins)
    // {
    //     // Récupérer la demande d'approvisionnement par son ID
    //     $demande = Nouveaubesoins_departement::findOrFail($id_nouveaubesoins);

    //     // Vérifier si la demande est "Transférée"
    //     if ($demande->id_status === 4) { // Si le statut est "Transféré"
    //         $demande->id_status = 2; // Changer le statut à "Validée"
    //         $demande->save();

    //         // Rediriger avec un message de succès
    //         return redirect()->back()->with('success', 'La demande a été validée avec succès.');
    //     }

    //     // Si la demande n'était pas "Transférée"
    //     return redirect()->back()->with('error', 'Cette demande est déjà validée ou ne peut pas être validée.');
    // }

    //? departement 
    public function create_departement()
    {

        return view('BackOffice.insert_departement');
    }
    // Fonction pour gérer l'insertion d'un nouveau département
    public function traitement_departement(Request $request)
    {
        // Validation des données envoyées
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        // Nettoyer les espaces superflus autour du nom
        $validatedData['nom'] = trim($validatedData['nom']);

        // Insertion du département
        Departement::create([
            'nom_departement' => $validatedData['nom'],
        ]);

        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Département inséré avec succès.');
    }

    public function liste_departement()
    {

        $departements = Departement::all();
        return view('BackOffice.list_departement', compact('departements'));
    }
    public function liste_poste()
    {

        $postes = Poste::all();

        return view('BackOffice.poste', compact('postes'));
    }




}


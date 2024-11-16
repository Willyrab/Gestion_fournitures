<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\EntreeArticle;
use App\Models\MouvementEntreeArticle;
use App\Models\V_entree_article;
use App\Models\SortieArticle;
use App\Models\V_sortie_article;
use App\Models\V_stock_actuel;
use App\Models\V_demande_appro;
use App\Models\Categorie;
use App\Models\DemandeAchat;
use App\Models\Demande_approvisionnement;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    //? verification ok !
    public function CreateArticle(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|string|max:50',
            'description' => 'required|string',
            'unite' => 'required|string|max:50',
            'id_categorie' => 'required|integer|exists:categorie,id_categorie',
        ]);

        $id_gc = Auth::guard('manager')->id();

        // Création d'un nouvel article
        $article = new Article();
        $article->nom_article = trim($request->input('nom'));
        $article->reference = trim($request->input('reference'));
        $article->description = trim($request->input('description'));
        $article->unite = trim($request->input('unite'));
        $article->id_gc = $id_gc;
        $article->id_categorie = $request->input('id_categorie');

        try {
            // Enregistrement de l'article dans la base de données
            $article->save();

            return redirect()->route('insert_article.manager')->with('success', 'Article ajouté avec succès.');
        } catch (QueryException $e) {
            // Vérifiez si l'erreur est une violation de clé unique
            if ($e->getCode() == '23505') { // Code d'erreur pour violation de contrainte unique
                return redirect()->back()->withErrors(['reference' => 'Cette référence existe déjà.'])->withInput();
            }

            // Pour d'autres erreurs, renvoyez un message générique
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de l\'article.'])->withInput();
        }
    }

    //? verification ok!
    public function listArticles(Request $request)
    {

        $query = Article::query();

        if ($request->filled('nom_article')) {
            $query->whereRaw('LOWER(nom_article) LIKE ?', ['%' . strtolower(trim($request->input('nom_article'))) . '%']);
        }

        if ($request->filled('reference')) {
            $query->whereRaw('LOWER(reference) LIKE ?', ['%' . strtolower($request->input('reference')) . '%']);
        }
        // Filtrer par catégorie (ID de la catégorie sélectionnée)
        if ($request->filled('categorie')) {
            $query->where('id_categorie', $request->input('categorie'));
        }
        $managerId = Auth::guard('manager')->id();

        // Pagination avec ajout des paramètres de recherche
        $categories = Categorie::all();
        $articles = $query->where('id_gc', $managerId)->orderBy('nom_article', 'ASC')->paginate(5)->appends($request->all());

        return view('FrontOffice.article_list', compact('articles', 'categories'));
    }

    //? verification ok!
    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'nom_article' => 'required|string|max:255',
            'reference' => 'required|string|max:50',
            'description' => 'nullable|string',
            'unite' => 'required|string|max:50',
        ]);

        // Mise à jour des propriétés de l'article
        $article->nom_article = $request->nom_article;
        $article->reference = $request->reference;
        $article->description = $request->description;
        $article->unite = $request->unite;
        $article->save();

        return redirect()->route('articles.list')->with('success', 'Article modifié avec succès');
    }

    public function EntreeArticle(Request $request)
    {
        // Validation des champs
        $request->validate([
            'id_article' => 'required|integer|exists:articles,id_article',
            'quantite' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'id_fournisseur' => 'required|integer|exists:fournisseurs,id_fournisseur',

        ]);

        // Supprimer les espaces avant et après les inputs
        $quantite = trim($request->input('quantite'));
        $prix = trim($request->input('prix'));

        // Récupérer l'id du gestionnaire actuellement connecté
        $id_gc = Auth::guard('manager')->id();

        // Récupérer les informations de l'article sélectionné
        $article = Article::findOrFail($request->input('id_article'));

        // Créer une nouvelle entrée d'article dans la table entree_articles
        $entreeArticle = new EntreeArticle();
        $entreeArticle->id_article = $article->id_article;
        $entreeArticle->quantite = $quantite;
        $entreeArticle->prix = $prix;
        $entreeArticle->id_gc = $id_gc; // Gestionnaire qui fait l'entrée
        $entreeArticle->id_fournisseur = $request->input('id_fournisseur');
        $entreeArticle->date_entree = now();
        $entreeArticle->save();

        // Créer une nouvelle entrée dans la table mouvement_entree_article
        $mouvementEntreeArticle = new MouvementEntreeArticle();
        $mouvementEntreeArticle->id_article = $article->id_article;
        $mouvementEntreeArticle->nom_article = $article->nom_article;
        $mouvementEntreeArticle->description_article = $article->description;
        $mouvementEntreeArticle->unite = $article->unite;
        $mouvementEntreeArticle->reference = $article->reference;
        $mouvementEntreeArticle->quantite = $quantite;
        $mouvementEntreeArticle->prix = $prix;
        $mouvementEntreeArticle->id_gc = $id_gc;
        $mouvementEntreeArticle->id_fournisseur = $request->input('id_fournisseur');
        $mouvementEntreeArticle->date_entree = $entreeArticle->date_entree;
        $mouvementEntreeArticle->save();

        // Rediriger avec un message de succès
        return redirect()->route('entree.article')->with('success', 'Article entré avec succès et historique enregistré.');
    }

    public function insertSortieArticle(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'id_article' => 'required|exists:articles,id_article',
            'quantite' => 'required|integer|min:1',
            'id_departements' => 'required|exists:departements,id_departement',
        ]);

        // Récupérer l'ID de l'article et la quantité demandée
        $id_article = $request->id_article;
        $quantite_demandee = $request->quantite;

        // Récupérer l'ID du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Vérifier si la quantité demandée est disponible dans le stock actuel
        $stock_actuel = V_stock_actuel::where('id_article', $id_article)->first();

        if (!$stock_actuel || $stock_actuel->stock_actuel < $quantite_demandee) {
            // Si la quantité n'est pas suffisante, renvoyer une erreur
            return redirect()->back()->withErrors(['quantite' => 'Quantité insuffisante pour cet article.']);
        }

        // Insérer les données dans la table sortie_articles
        SortieArticle::create([
            'id_article' => $request->id_article,
            'quantite' => $request->quantite,
            'id_gc' => $id_gc, // Le gestionnaire connecté
            'id_responsable_departement' => $request->id_departements,
        ]);

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Sortie d\'article enregistrée avec succès.');
    }
    //? historique entree 
    public function afficherHistoriqueGestionnaire(Request $request)
    {
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Valider les dates (si elles sont fournies)
        $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ], [
            // Message d'erreur personnalisé
            'date_fin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
        ]);

        // Initialiser la requête de base
        $query = V_entree_article::where('id_gc', $id_gc);

        // Filtrer par date de début si seulement 'date_debut' est fourni
        if ($request->filled('date_debut') && !$request->filled('date_fin')) {
            $query->whereDate('date_entree', '>=', $request->date_debut);
        }

        // Filtrer par date de fin si seulement 'date_fin' est fourni
        if ($request->filled('date_fin') && !$request->filled('date_debut')) {
            $query->whereDate('date_entree', '<=', $request->date_fin);
        }

        // Filtrer par plage de dates si les deux dates sont fournies
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_entree', [$request->date_debut, $request->date_fin]);
        }

        // Récupérer les résultats
        $historique_entrees = $query->paginate(6);

        // Passer les données à la vue
        return view('FrontOffice.historique_entree', compact('historique_entrees'));
    }

    public function afficherSortiesArticles(Request $request)
    {
        $id_gc = Auth::guard('manager')->id();

        // Valider les dates (si elles sont fournies)
        $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ], [
            'date_fin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
        ]);

        // Récupérer les sorties d'articles depuis la vue
        $query = V_sortie_article::where('gestionnaire_id', $id_gc);

        // Filtrer par date de début si seulement 'date_debut' est fourni
        if ($request->filled('date_debut') && !$request->filled('date_fin')) {
            $query->whereDate('date_sortie', '>=', $request->date_debut);
        }

        // Filtrer par date de fin si seulement 'date_fin' est fourni
        if ($request->filled('date_fin') && !$request->filled('date_debut')) {
            $query->whereDate('date_sortie', '<=', $request->date_fin);
        }

        // Filtrer par plage de dates si les deux dates sont fournies
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_sortie', [$request->date_debut, $request->date_fin]);
        }

        // Récupérer les résultats
        $historique_sorties = $query->paginate(6);

        // Passer les données à la vue Blade
        return view('FrontOffice.historique_sortie', compact('historique_sorties'));
    }

    public function showStockActuel(Request $request)
    {
        // Récupérer toutes les catégories pour le formulaire

        $categories = Categorie::all();
        $id_gc = Auth::guard('manager')->id();

        // Si une catégorie est sélectionnée, filtrer les résultats
        $id_categorie = $request->input('id_categorie');
        if ($id_categorie) {
            // Filtrer les stocks par catégorie et gestionnaire de centre
            $stocks = V_stock_actuel::where('id_categorie', $id_categorie)
                ->where('gestionnaire_entree', $id_gc)
                ->get();
        } else {
            // Afficher uniquement les stocks du gestionnaire connecté
            $stocks = V_stock_actuel::where('gestionnaire_entree', $id_gc)->get();
        }

        // Retourner la vue avec les résultats
        return view('FrontOffice.stockActuel', compact('categories', 'stocks'));
    }
    public function showStockCritique()
    {
        // Récupérer toutes les catégories pour éventuellement les afficher dans la vue
        $categories = Categorie::all();

        $id_gc = Auth::guard('manager')->id();
        // Récupérer les articles nécessitant un approvisionnement via la méthode du modèle
        $stocks = V_stock_actuel::articlesEnApprovisionnement($id_gc);

        // Retourner la vue avec les résultats
        return view('FrontOffice.stockCritique', compact('categories', 'stocks'));
    }

    public function demanderApprovisionnement(Request $request)
    {
        // Valider que l'article est bien passé dans la requête
        $request->validate([
            'id_article' => 'required|integer|exists:articles,id_article',
        ]);

        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Vérifier si une demande pour cet article est déjà en attente
        $demandeExistante = Demande_approvisionnement::where('id_article', $request->id_article)
            ->where('id_gc', $id_gc)
            ->whereIn('id_status', [1]) // Status 1: En attente, 2: En cours (à définir selon tes statuts)
            ->first();

        // Si une demande existe déjà, retourner avec un message d'avertissement
        if ($demandeExistante) {
            return redirect()->back()->with('warning', 'Une demande d\'approvisionnement est déjà en cours pour cet article.');
        }

        // Créer une nouvelle demande d'approvisionnement
        Demande_approvisionnement::create([
            'id_article' => $request->id_article,
            'id_gc' => $id_gc,
            'id_status' => 1, // "En attente" par défaut
        ]);

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Demande d\'approvisionnement envoyée avec succès.');
    }

    public function afficherdemande_approvisionnement(Request $request)
    {
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Créer une requête de base pour récupérer l'historique des demandes d'approvisionnement pour ce gestionnaire
        $query = V_demande_appro::where('id_gc', $id_gc);

        // Filtrer par référence d'article si spécifié
        if ($request->filled('reference_article')) {
            $reference_article = trim($request->input('reference_article')); // Utiliser trim()
            $query->whereRaw('LOWER(reference_article) LIKE ?', ['%' . strtolower($reference_article) . '%']);
        }

        // Filtrer par nom d'article si spécifié
        if ($request->filled('nom_article')) {
            $nom_article = trim($request->input('nom_article')); // Utiliser trim()
            $query->whereRaw('LOWER(nom_article) LIKE ?', ['%' . strtolower($nom_article) . '%']);
        }

        // Filtrer par date de demande si spécifié
        if ($request->filled('date_demande')) {
            $query->whereDate('date_demande', $request->date_demande);
        }

        // Exécuter la requête avec pagination
        $demandeapprovisionnements = $query->paginate(5)->appends($request->all());

        // Passer les données à la vue
        return view('FrontOffice.demandeapprovisionnement', compact('demandeapprovisionnements'));
    }

    public function getArticleDetails($id)
    {
        // Récupérer uniquement les détails de l'article
        $article = Article::find($id);

        // Vérifier si l'article existe
        if (!$article) {
            return response()->json(['message' => 'Article non trouvé'], 404);
        }

        // Renvoyer les détails de l'article sous format JSON
        return response()->json(['article' => $article]);
    }

}


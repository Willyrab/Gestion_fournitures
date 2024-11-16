<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SortieArticle;
use App\Models\Article;
use Carbon\Carbon;
use App\Models\V_sortie_article;
use Illuminate\Support\Facades\Auth;


class PrevisionController extends Controller
{
    // Fonction pour afficher la page de prévision
    public function index(Request $request)
    {
        $managerId = Auth::guard('manager')->id();
        // Récupérer tous les articles du gestionnaire
        $articles = Article::select('nom_article', 'unite')
            ->where('id_gc', $managerId) // Filtrer par manager_id
            ->get();

        // Vérifiez si des articles existent
        if ($articles->isEmpty()) {
            return view('FrontOffice.prevision', [
                'historique' => [],
                'previsions' => [],
                'articles' => $articles,
                'article' => null,
                'unite' => null
            ]);
        }
        // Récupérer l'article sélectionné (par défaut, le premier article s'il est nul)
        $selectedArticle = $request->get('article', $articles->first()->nom_article);
        // Trouver l'article correspondant pour récupérer son unité
        $articleData = $articles->firstWhere('nom_article', $selectedArticle);
        $unite = $articleData ? $articleData->unite : null; // Récupérer l'unité    

        // Récupérer les données historiques (dernière année par exemple)
        $historique = $this->recupererConsommationHistorique($selectedArticle, $managerId);
        // Calculer les prévisions à partir des données historiques (prévision pour les 3 prochains mois)
        $previsions = $this->calculerPrevision($historique);
        // Arrondir la prévision pour le mois suivant
        $previsionArrondie = round($previsions[0]);
        // Envoyer les données à la vue
        return view('FrontOffice.prevision', compact('historique', 'previsionArrondie', 'articles', 'selectedArticle', 'unite'));
        // Envoyer les données à la vue

    }
    public function recupererConsommationHistorique($article, $managerId)
    {
        $historique = SortieArticle::select('id_article', 'quantite', 'date_sortie')
            ->when($article, function ($query) use ($article) {
                return $query->whereHas('article', function ($q) use ($article) {
                    $q->where('nom_article', $article);
                });
            })
            ->where('id_gc', $managerId)
            ->where('date_sortie', '>=', Carbon::now()->subMonths(12))
            ->orderBy('date_sortie')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date_sortie)->format('m'); // Grouper par mois
            });

        // Convertir la collection en tableau pour utiliser ksort
        $historiqueArray = $historique->toArray();

        // Remplir les mois manquants
        $mois = range(1, 12);
        foreach ($mois as $moisNum) {
            $moisKey = str_pad($moisNum, 2, '0', STR_PAD_LEFT); // Format '01', '02', etc.
            if (!isset($historiqueArray[$moisKey])) {
                $historiqueArray[$moisKey] = []; // Créer un tableau vide pour le mois manquant
            }
        }

        // Trier par clé de mois
        ksort($historiqueArray);

        // Convertir à nouveau en collection pour la suite du traitement
        $historique = collect($historiqueArray);

        return $historique;
    }

    public function calculerPrevision($historique)
    {
        // Filtrer les mois qui contiennent des données
        $historiqueRempli = $historique->filter(function ($donnees) {
            return count($donnees) > 0; // Garder uniquement les mois avec des données
        });

        // Prendre les trois derniers mois
        $derniersMois = $historiqueRempli->slice(-3);

        // Initialiser les variables pour le calcul
        $totalSortie = 0;
        $nombreDeMois = count($derniersMois);

        // Calculer la somme des quantités sorties sur la période
        foreach ($derniersMois as $donnees) {
            $totalSortie += array_sum(array_column($donnees, 'quantite'));
        }

        // Prévenir la division par zéro
        if ($nombreDeMois === 0) {
            return [0, 0, 0]; // Retourner zéro si aucun mois n'est disponible
        }

        // Moyenne des sorties pour les trois derniers mois
        $moyenneSortie = $totalSortie / $nombreDeMois;

        // Prévisions pour les trois prochains mois
        $previsionProchainsMois = [];
        for ($i = 0; $i < 3; $i++) {
            $previsionProchainsMois[] = round($moyenneSortie, 2); // Estimation pour les mois suivants
        }

        return $previsionProchainsMois;
    }


    public function suivis_article(Request $request)
    {
        // Récupérer l'id du gestionnaire connecté
        $id_gc = Auth::guard('manager')->id();

        // Filtrer les articles et les fournisseurs en fonction de cet id_gc
        $articles = Article::where('id_gc', $id_gc)->get();

        // Récupérer la consommation des articles
        $consommationsQuery = V_sortie_article::where('gestionnaire_id', $id_gc);

        if ($request->has('article') && $request->article) {
            $consommationsQuery->where('nom_article', $request->article);
        }
        // Filtrer par date de début et date de fin si spécifiées
        if ($request->has('date_debut') && $request->date_debut) {
            $consommationsQuery->whereDate('date_sortie', '>=', $request->date_debut);
        }
        if ($request->has('date_fin') && $request->date_fin) {
            $consommationsQuery->whereDate('date_sortie', '<=', $request->date_fin);
        }

        // Utiliser paginate pour la pagination
        $consommations = $consommationsQuery->paginate(10)->appends($request->all());
        // Grouper les consommations par département pour le graphique
        $groupedData = $consommationsQuery->selectRaw('nom_departement, SUM(quantite) as total')
            ->groupBy('nom_departement')
            ->pluck('total', 'nom_departement');

        return view('FrontOffice.consommationDepartement', compact('articles', 'consommations', 'groupedData'));
    }

    public function index_prevision(Request $request)
    {
        // Récupérer les postes uniques
        $postes = V_sortie_article::select('id_poste', 'nom_poste')->distinct()->get();
    
        // Initialisation des variables
        $articles = [];
        $historique = collect(); // Assurez-vous que $historique est initialisé comme collection
        $previsions = [];
        $selectedArticle = null;
        $unite = null;
        $previsionArrondie = 0;
    
        if ($request->has('poste') && $request->poste) {
            // Récupérer les articles pour le poste sélectionné
            $articles = V_sortie_article::select('nom_article', 'unite')
                                        ->where('id_poste', $request->poste)
                                        ->distinct()
                                        ->get();
    
            // Vérifiez si des articles existent
            if (!$articles->isEmpty()) {
                // Récupérer l'article sélectionné (par défaut, le premier article s'il est nul)
                $selectedArticle = $request->get('article', $articles->first()->nom_article);
                // Trouver l'article correspondant pour récupérer son unité
                $articleData = $articles->firstWhere('nom_article', $selectedArticle);
                $unite = $articleData ? $articleData->unite : null;
    
                // Récupérer les données historiques
                $historique = $this->recupererConsommationHistoriqueAdmin($selectedArticle, $request->poste);
                // Calculer les prévisions à partir des données historiques
                $previsions = $this->calculerPrevision($historique);
    
                // Arrondir la prévision pour le mois suivant
                $previsionArrondie = !empty($previsions) ? round($previsions[0]) : 0;
            }
        }
    
        return view('BackOffice.prevision', compact('postes', 'articles', 'historique', 'previsions', 'selectedArticle', 'unite', 'previsionArrondie'));
    }
    
    public function recupererConsommationHistoriqueAdmin($article, $poste)
    {
        $historique = V_sortie_article::select('id_article', 'quantite', 'date_sortie')
            ->where('nom_article', $article)
            ->where('id_poste', $poste)
            ->where('date_sortie', '>=', Carbon::now()->subMonths(12))
            ->orderBy('date_sortie')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date_sortie)->format('m'); // Grouper par mois
            });
    
        // Convertir la collection en tableau pour utiliser ksort
        $historiqueArray = $historique->toArray();
    
        // Remplir les mois manquants
        $mois = range(1, 12);
        foreach ($mois as $moisNum) {
            $moisKey = str_pad($moisNum, 2, '0', STR_PAD_LEFT); // Format '01', '02', etc.
            if (!isset($historiqueArray[$moisKey])) {
                $historiqueArray[$moisKey] = []; // Créer un tableau vide pour le mois manquant
            }
        }
    
        // Trier par clé de mois
        ksort($historiqueArray);
    
        // Convertir à nouveau en collection pour la suite du traitement
        return collect($historiqueArray);
    }
    

    






}

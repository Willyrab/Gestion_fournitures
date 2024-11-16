<?php

use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\RedirectIfAuthenticatedManager;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PrevisionController;
use App\Http\Controllers\DepartementController;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('guest.manager')->group(function () {

    Route::get('/', function () {
        return view('FrontOffice.login');
    })->name('login.manager');

    Route::get('/register', function () {
        return view('FrontOffice.register');
    });
});

//? Admin
Route::get('/BackOffice/Login', [AuthController::class, 'show_Login_Admin'])->name('login.admin');
Route::post('/BackOffice/treatment', [AuthController::class, 'login_Admin'])->name('connexion.Admin');

Route::middleware('auth.admin')->group(function () {
    Route::get('/BackOffice/Index', [AuthController::class, 'show_Index_Admin'])->name('index.admin');
    Route::post('/logout-admin', [AuthController::class, 'logout_admin'])->name('logout.admin');
    Route::get('/BackOffice/liste-manager', [AdminController::class, 'list_gestionnaire_centre'])->name('gestionnaire.centre');
    Route::get('/BackOffice/admin/demandes-approvisionnement', [AdminController::class, 'approvisionnement_avalider'])->name('demande.approvisionnement.admin');
    Route::put('/BackOffice/demande-approvisionnement/{id_demandeapp}/valider', [AdminController::class, 'validerDemande'])->name('demande.valider');
    Route::get('/BackOffice/admin/demandes-achats', [AdminController::class, 'lister_demandeAchat'])->name('demande.Achat.admin');
    Route::put('/BackOffice/demande-achat/{id_demande}/valider', [AdminController::class, 'validerDemandeAchat'])->name('valider.demandeAchat');
    Route::get('/BackOffice/admin/fournisseurs-articles', [AdminController::class, 'lister_FournisseurArticle'])->name('fournisseur.articles');
    // Route::get('/api/article/{id_article}', [AdminController::class, 'getArticleDetails']); 
    Route::get('/BackOffice/admin/achat-fournisseurs', [AdminController::class, 'Achats_Fournisseur'])->name('achat.fournisseur');
    Route::post('/BackOffice/traitement-demandeachat', [AdminController::class, 'insert_achat'])->name('traitement.achatfournisseur');

    Route::get('/BackOffice/list-bon-commande', [AdminController::class, 'afficherbon_commande'])->name('achat.list.bc');
    Route::get('/BackOffice/bon-de-commande/{id_achat}/export', [AdminController::class, 'generateBonCommandePDF'])->name('generate.boncommande.pdf');
    Route::put('/BackOffice/bon-de-commande/{id_achat}/valider', [AdminController::class, 'updateBcstatus'])->name('valider.bonde.commande');


    Route::delete('/BackOffice/bon-de-commande/{id}', [AdminController::class, 'destroyBC'])->name('master');
    Route::put('/BackOffice/bon-de-commande/{id}/update', [AdminController::class, 'updateBC'])->name('update.BC');

    Route::get('/BackOffice/admin/demande-devis', [AdminController::class, 'Demander_devis'])->name('creer_demande.devis');
    Route::post('/BackOffice/traitement-demande-devis', [AdminController::class, 'sendDevis'])->name('traitement.demandedevis');
    Route::get('/BackOffice/admin/list-demande-devis', [AdminController::class, 'afficherdemande_devis'])->name('list.devis');
    Route::delete('/BackOffice/demande-devis/{id}/delete', [AdminController::class, 'destroyDevis'])->name('delete.devis');
    Route::get('/BackOffice/demande-devis/export/{id_demande}', [AdminController::class, 'generateDevisPDF'])->name('generate.devis.pdf');
    Route::put('/BackOffice/demande-devis/update/{id_demande}', [AdminController::class, 'updateDemandeDevis'])->name('update.devis');
    Route::put('/BackOffice/demande-devis/{id_demande}/approuver', [AdminController::class, 'updateDevistatus'])->name('approuver.devis');

     //? liste demande nouveau 
   // Route::get('/demande-nouveaux-articles', [AdminController::class, 'lister_nouveau_besoins'])->name('demande.nouveau_artcile.admin');
    Route::put('/BackOffice/nouveaubesoins/approuver/{id_nouveaubesoins}', [AdminController::class, 'approuver_NDarticle'])->name('approuver.NDarticle');

    Route::get('/BackOffice/inserer-departement', [AdminController::class, 'create_departement'])->name('creation.departement');
    Route::post('/BackOffice/departement/store', [AdminController::class, 'traitement_departement'])->name('traitement.departement');
    Route::get('/BackOffice/list-departement', [AdminController::class, 'liste_departement'])->name('liste.departement');
    Route::get('/BackOffice/list-poste', [AdminController::class, 'liste_poste'])->name('liste.poste');
    
    Route::get('/BackOffice/prevision-article', [PrevisionController::class, 'index_prevision'])->name('prevision.admin');


});

//? Gestionnaire de centre  

Route::get('/FrontOffice/register', [AuthController::class, 'show_manager_registration'])->name('view_register.manager');
Route::post('/FontOffice/treatment-register', [AuthController::class, 'treatment_manager_registration'])->name('manager.registration');
Route::post('/FontOffice/treatment', [AuthController::class, 'login_Manager'])->name('connexion.manager');
Route::get('/FrontOffice/recuperer-mot-de-passe', [ManagerController::class, 'forget_password'])->name('forget_password.gc');



Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('sendmdp.manager');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');




Route::middleware('auth.manager')->group(function () {
    Route::get('/FrontOffice/Index', [AuthController::class, 'show_Index_Manager'])->name('index.manager');
    Route::post('/logout-manager', [AuthController::class, 'logout_manager'])->name('logout.manager');
    Route::get('/FrontOffice/insert-article', [ManagerController::class, 'show_Insert_Article'])->name('insert_article.manager');
    Route::post('/FrontOffice/treatment-article', [ArticleController::class, 'CreateArticle'])->name('create.articles');
    Route::get('/FrontOffice/article-list', [ArticleController::class, 'listArticles'])->name('articles.list');
    Route::get('/FrontOffice/insert-fournisseur', [FournisseurController::class, 'form_insert_fournisseur'])->name('insert_fournisseur.manager');
    Route::post('/FrontOffice/treatment-fournisseur', [FournisseurController::class, 'CreateFournisseur'])->name('create.fournisseurs');
    Route::get('/FrontOffice/fournisseur-list', [FournisseurController::class, 'listFournisseurs'])->name('fournisseurs.list');
    Route::put('/FrontOffice/fournisseur/update/{id_fournisseur}', [FournisseurController::class, 'updateFournisseur'])->name('update.fournisseur');


    Route::get('/FrontOffice/Entree-Stock-article', [ManagerController::class, 'Form_EntreeStock_Article'])->name('entree.article');
    Route::post('/FrontOffice/treatment-entree-article', [ArticleController::class, 'EntreeArticle'])->name('insert.entreeArticle');
    Route::put('/FrontOffice/articles/{id_article}', [ArticleController::class, 'updateArticle'])->name('article.update');
    Route::get('/FrontOffice/historique-mouvement-entree-article', [ArticleController::class, 'afficherHistoriqueGestionnaire'])->name('mouvement.entree');
    Route::get('/FrontOffice/sortie-Stock-article', [ManagerController::class, 'Form_SortieStock_Article'])->name('sortie.article');
    Route::post('/FrontOffice/insertSortieArticle', [ArticleController::class, 'insertSortieArticle'])->name('insert.sortieArticle');
    Route::get('/FrontOffice/historique-mouvement-sorties-articles', [ArticleController::class, 'afficherSortiesArticles'])->name('historiques.sorties');
    Route::get('/FrontOffice/stock-actuel', [ArticleController::class, 'showStockActuel'])->name('stock.actuel');
    Route::get('/FrontOffice/stock-faible', [ArticleController::class, 'showStockCritique'])->name('stock.critique');
    Route::post('/FrontOffice/demande-approvisionnement', [ArticleController::class, 'demanderApprovisionnement'])->name('demande.approvisionnement');
    Route::get('/FrontOffice/demande-approvisonnement', [ArticleController::class, 'afficherdemande_approvisionnement'])->name('demande.aprovisonnement');
    
    Route::get('/FrontOffice/prevision', [PrevisionController::class, 'index'])->name('prevision');
    Route::get('/FrontOffice/consommatio-departement', [PrevisionController::class, 'suivis_article'])->name('consommation.departement');

    Route::get('/FrontOffice/demande-achat', [ManagerController::class, 'Demande_Article_Admin'])->name('demandeAchat.gc');
    Route::post('/FrontOffice/traitement-demande', [ManagerController::class, 'sendDemande_achat'])->name('traitement.demandeachat');
    Route::get('/FrontOffice/liste-demande-achats', [ManagerController::class, 'afficherdemande_achat'])->name('list.demandeachat');
    Route::put('/FrontOffice/DA/{id}', [ManagerController::class, 'updateDA'])->name('modifier.DA');
    Route::get('/FrontOffice/list-besoins-departements', [ManagerController::class, 'listerDemandeArticle'])->name('list.besoinsDepartement');
    Route::put('/FrontOffice/demande-besoins-article/{id_besoins}/valider', [ManagerController::class, 'validerDemandeArticle'])->name('valider.besoins');
    Route::get('/FrontOffice/demande-nouveau-artciles', [ManagerController::class, 'lister_nouveau_besoins'])->name('demande.nouveauarticle');
    Route::put('/FrontOffice/demande/transferer/{id}', [ManagerController::class, 'transfererDemande'])->name('transferer.demande');

});

//? departement :
Route::get('/Departement/Login', [DepartementController::class, 'show_Login_Departement'])->name('login.departement');
Route::get('/Departement/register', [DepartementController::class, 'show_Departement_registration'])->name('view_register.departemenet');
Route::post('/Departement/treatment-register', [DepartementController::class, 'treatment_departement_registration'])->name('departement.registration');
Route::post('/Departement/treatment-login', [DepartementController::class, 'login_departement'])->name('connexion.departement');

Route::middleware('auth.departement')->group(function () {
    Route::get('/Departement/Index', [DepartementController::class, 'show_Index_Departement'])->name('index.departement');
    Route::post('/logout-departement', [DepartementController::class, 'logout_departement'])->name('logout.departement');
    Route::get('/Departement/demande-besoins', [DepartementController::class, 'demande_article'])->name('Form_besoins.departement');
    Route::get('/Departement/demande-nouveau-besoins', [DepartementController::class, 'demander_nouveau_article'])->name('nouveau_article.departement');
    Route::post('Departement/traitement/demande-besoins', [DepartementController::class, 'traitementdemande_article'])->name('traitement_besoins.departement');
    Route::post('Departement/traitement/demande-nouveau-besoins', [DepartementController::class, 'traitementdemande_nouveau_article'])->name('traitement_nouveaubesoins.departement');
    Route::get('/Departement/liste-demande-besoins', [DepartementController::class, 'listerdemande_article'])->name('liste_besoins.departement');
    Route::get('/Departement/liste-nouveau-demande-article', [DepartementController::class, 'lister_NDarticle'])->name('liste_NDarticle.departement');

    Route::put('/Departement/edit-article/{id}', [DepartementController::class, 'updateDemandeArticle'])->name('update.demande');

});

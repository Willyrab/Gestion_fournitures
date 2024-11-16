<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseurs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class FournisseurController extends Controller
{
    public function form_insert_fournisseur(){
        return view("FrontOffice.insert_fournisseur");
    }

     //? verification ok!
    public function listFournisseurs(Request $request)
    {
    
    
        $query = Fournisseurs::query();
    
        if ($request->filled('nom_fournisseur')) {
            $query->whereRaw('LOWER(nom_fournisseur) LIKE ?', ['%' . strtolower($request->input('nom_fournisseur')) . '%']);
        }
        if ($request->filled('lieu_fournisseur')) {
            $query->whereRaw('LOWER(lieu_fournisseur) LIKE ?', ['%' . strtolower($request->input('lieu_fournisseur')) . '%']);
        }
        if ($request->filled('contact')) {
            $query->whereRaw('LOWER(contact) LIKE ?', ['%' . strtolower($request->input('contact')) . '%']);
        }
        $managerId = Auth::guard('manager')->id();
        
        // Pagination avec ajout des paramètres de recherche
        $fournisseurs = $query->where('id_gc',$managerId)->orderBy('nom_fournisseur','ASC')->paginate(5)->appends($request->all());
    
        return view('FrontOffice.fournisseur_list', compact('fournisseurs'));
    }

    //? verification ok
    public function CreateFournisseur(Request $request){
       
        $request->validate([
            'nom' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:fournisseurs,email',
            'contact' => 'required|numeric|min:0|digits_between:8,10',  // Ajustez la plage de longueur selon vos besoins
        ]);
        
        // ce qui est connecté
        $id_gc = Auth::guard('manager')->id();
        
        $fournisseur = new Fournisseurs();
        $fournisseur->nom_fournisseur = trim($request->input('nom'));
        $fournisseur->lieu_fournisseur = trim($request->input('lieu'));
        $fournisseur->email = trim($request->input('email'));
        $fournisseur->contact = trim($request->input('contact'));
        $fournisseur->id_gc = $id_gc;  // Associer l'id du gestionnaire au fournisseur
    
        try {
            // Enregistrement de l'article dans la base de données
            $fournisseur->save();
    
            // Redirection après succès de l'insertion avec un message de succès
            return redirect()->route('insert_fournisseur.manager')->with('success', 'Fournisseur ajouté avec succès');

        } catch (QueryException $e) {
            // Vérifiez si l'erreur est une violation de clé unique
        
            // Pour d'autres erreurs, renvoyez un message générique
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l\'ajout de fournisseur.'])->withInput();
        }
    }

   //? verification ok!
    public function updateFournisseur(Request $request, $id_fournisseur)
    {
        // Récupérer le fournisseur par son ID
        $fournisseur = Fournisseurs::findOrFail($id_fournisseur);
    
        // Validation des données reçues
        $request->validate([
            'nom_fournisseur' => 'required|string|max:255',
            'lieu_fournisseur' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:fournisseurs,email,' . $id_fournisseur . ',id_fournisseur', // Notez cette ligne
            'contact' => 'required|numeric|min:0|digits_between:8,10',
        ]);
    
        // Mettre à jour les propriétés du fournisseur
        $fournisseur->nom_fournisseur = trim($request->input('nom_fournisseur'));
        $fournisseur->lieu_fournisseur = trim($request->input('lieu_fournisseur'));
        $fournisseur->email = trim($request->input('email'));
        $fournisseur->contact = trim($request->input('contact'));
        $fournisseur->save();
    
        // Redirection vers la liste avec un message de succès
        return redirect()->route('fournisseurs.list')->with('success', 'Fournisseur modifié avec succès.');
    }
    
}

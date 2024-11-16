@extends('BackOffice.layouts.app')
@section('title', 'Réinitialisation base de données')

@section('head')
    <meta name="description" content="page pour réinitialiser base de données">
    <meta name="keywords" content="Réinitialisation base de données">
@endsection

@section('content') 
<div id="content" class="p-4 p-md-5">
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="bg-light p-4 shadow" action="{{ route('reset-database')}}" method="post" enctype="multipart/form-data">
                    @csrf 
                    <h2 class="mb-4 text-center">Reinitialisé la base de donnée</h2>
                  
                    
                    <div class="mt-4 mb-3 row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block" name="import">Executer</button>
                        </div>
                    </div>
                </form>
            

            </div>
        </div>
    </div>
</div>

@endsection

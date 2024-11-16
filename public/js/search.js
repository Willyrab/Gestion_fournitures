    // Fonction pour mettre à jour le tableau des élèves avec les résultats de la recherche
    // function updateListeEleves(resultats) {
    //     // Sélectionnez le corps du tableau
    //     var tbody = document.querySelector('table.table tbody');

    //     // Sélectionnez l'élément de pagination
    //     var pagination = document.querySelector('.pagination');

    //     // Initialise une chaîne vide pour stocker le code HTML du corps du tableau
    //     var html = '';

    //     // Vérifiez si la liste des résultats est vide
    //     if (Array.isArray(resultats)) { // Vérifiez si resultats est un tableau
    //         if (resultats.length === 0) {
    //             html += '<tr><td colspan="4">Aucun résultat trouvé.</td></tr>';
    //         } else {
    //             resultats.forEach(function(eleve) {
    //                 var detailsUrl = '/eleves/' + eleve.id;
    //                 var destroyUrl = '/eleves/' + eleve.id;
    //                 html += '<tr onclick="window.location=\'' + detailsUrl + '\';" style="cursor:pointer;">';
    //                 html += '<td>' + eleve.nom + '</td>';
    //                 html += '<td>' + eleve.prenoms + '</td>';
    //                 html += '<td>' + eleve.datenaissance + '</td>';
    //                 html += '<td>';
    //                 html += '<button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(' + eleve.id + ')">Modifier</button>';
    //                 html += '<form action="' + destroyUrl + '" method="POST" class="d-inline">';
    //                 html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
    //                 html += '<input type="hidden" name="_method" value="DELETE">';
    //                 html += '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet élève ?\')">Supprimer</button>';
    //                 html += '</form>';
    //                 html += '</td>';
    //                 html += '</tr>';
    //             });
    //         }
    //     } else {
    //         html += '<tr><td colspan="4">Aucun résultat trouvé.</td></tr>';
    //     }

    //     // Met à jour le contenu du corps du tableau avec le code HTML généré
    //     tbody.innerHTML = html;

    //     // Masquer la pagination lorsque des résultats sont affichés
    //     if (resultats.length > 0) {
    //         pagination.style.display = 'none';
    //     } else {
    //         pagination.style.display = 'block';
    //     }

    //     // Créer une nouvelle pagination personnalisée
    //     var customPagination = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    //     customPagination += '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
    //     for (var i = 1; i <= 5; i++) {
    //         customPagination += '<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>';
    //     }
    //     customPagination += '<li class="page-item"><a class="page-link" href="#">&raquo;</a></li>';
    //     customPagination += '</ul></nav>';

    //     // Mettre à jour le contenu de l'élément de pagination avec la pagination personnalisée
    //     pagination.innerHTML = resultats.length > 0 ? customPagination : '';

    // }



    // Fonction pour mettre à jour le tableau des élèves avec les résultats de la recherche
    function updateListeEleves(resultats) {
        // Sélectionnez le corps du tableau
        var tbody = document.querySelector('table.table tbody');

        // Sélectionnez l'élément de pagination
        var pagination = document.querySelector('.pagination');

        // Initialise une chaîne vide pour stocker le code HTML du corps du tableau
        var html = '';

        // Vérifiez si la liste des résultats est vide
        if (Array.isArray(resultats)) { // Vérifiez si resultats est un tableau
            if (resultats.length === 0) {
                html += '<tr><td colspan="4">Aucun résultat trouvé.</td></tr>';
            } else {
                resultats.forEach(function(eleve) {
                    var detailsUrl = '/eleves/' + eleve.id;
                    var destroyUrl = '/eleves/' + eleve.id;
                    html += '<tr onclick="window.location=\'' + detailsUrl + '\';" style="cursor:pointer;">';
                    html += '<td>' + eleve.nom + '</td>';
                    html += '<td>' + eleve.prenoms + '</td>';
                    html += '<td>' + eleve.datenaissance + '</td>';
                    html += '<td>';
                    html += '<button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(' + eleve.id + ')">Modifier</button>';
                    html += '<form action="' + destroyUrl + '" method="POST" class="d-inline">';
                    html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                    html += '<input type="hidden" name="_method" value="DELETE">';
                    html += '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet élève ?\')">Supprimer</button>';
                    html += '</form>';
                    html += '</td>';
                    html += '</tr>';
                });
            }
        } else {
            html += '<tr><td colspan="4">Aucun résultat trouvé.</td></tr>';
        }

        // Met à jour le contenu du corps du tableau avec le code HTML généré
        tbody.innerHTML = html;

        // Masquer la pagination lorsque des résultats sont affichés
        if (resultats.length > 0) {
            pagination.style.display = 'none';
        } else {
            pagination.style.display = 'block';
        }

        // Mettre à jour la pagination
        // Ici, vous pouvez ajouter votre code pour la pagination personnalisée
    }

    // // Fonction pour mettre à jour la pagination
    // function updatePagination(data) {
    //     var paginationElement = document.querySelector('.pagination');
    //     var paginationHTML = data.links; // Utilisez le HTML des liens de pagination de la réponse JSON
    
    //     // Mettez à jour le contenu de l'élément de pagination avec le HTML des liens de pagination
    //     paginationElement.innerHTML = paginationHTML;
    // }


    // Écouteur d'événements pour le chargement du DOM
    document.addEventListener('DOMContentLoaded', function() {
        // Écouteur d'événements pour la saisie dans la zone de recherche
        document.getElementById('searchInput').addEventListener('input', function() {
            var search = this.value; // Récupérer la valeur de la zone de recherche
            fetch('/recherche-multi-mot?search=' + search)// Effectuer une requête GET vers l'URL de recherche avec la valeur de la requête
                .then(response => response.json()) // Convertir la réponse en JSON
                .then(data => {
                    // Mettre à jour la liste des élèves avec les résultats de la recherche
                    updateListeEleves(data);

                     // Mettre à jour la pagination
                    // updatePagination(data);
                })
                .catch(error => {
                    // Gérer les erreurs
                    console.error(error);
                });
        });
    });


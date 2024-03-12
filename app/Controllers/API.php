<?php

    namespace App\Controllers;
    
    use CodeIgniter\RESTful\ResourceController;
    use CodeIgniter\API\ResponseTrait;
    
    use App\Models\Livre;
    use App\Models\Auteur;
    use App\Models\Serie;
    use App\Models\Client;
    
    class API extends BaseController {
        use ResponseTrait;

        /* ---------- LIVRES ---------- */

        public function getLivres(): \CodeIgniter\HTTP\ResponseInterface {
            $livres = Livre::all();
    
            foreach ($livres as $livre) {
                $livre -> auteur;
                $livre -> serie;
                $livre -> clients;
            }
    
            return $this -> respond($livres);
        }
    
        public function getLivreById(): \CodeIgniter\HTTP\ResponseInterface {
            $id = $this -> request -> getGet('id');
            $livre = Livre::find($id);
    
            if ($livre) {
                $livre -> auteur;
                $livre -> serie;
                $livre -> clients;
    
                return $this -> respond($livre);
            } else {
                return $this -> failNotFound("Livre non trouvé");
            }
        }
    
        public function getLivresByTitre(): \CodeIgniter\HTTP\ResponseInterface {
            $titre = $this -> request -> getGet('tit');
            $livres = Livre::where('titre', $titre) -> get();
    
            if ($livres) {
                foreach ($livres as $livre) {
                    $livre -> auteur;
                    $livre -> serie;
                    $livre -> clients;
                }
    
                return $this -> respond($livres);
            } else {
                return $this -> failNotFound("Livre non trouvé");
            }
        }
    
        public function getLivresByCategorie(): \CodeIgniter\HTTP\ResponseInterface {
            $categorie = $this -> request -> getGet('cat');
            $livres = Livre::where('categorie', $categorie) -> get();
    
            if ($livres) {
                foreach ($livres as $livre) {
                    $livre -> auteur;
                    $livre -> serie;
                    $livre -> clients;
                }
    
                return $this -> respond($livres);
            } else {
                return $this -> failNotFound("Livre non trouvé");
            }
        }
    
        public function getLivresBySerie(): \CodeIgniter\HTTP\ResponseInterface {
            $serie = $this -> request -> getGet('id');
            $livres = Livre::where('serie_id', $serie) -> get();
    
            if ($livres) {
                foreach ($livres as $livre) {
                    $livre -> auteur;
                    $livre -> serie;
                    $livre -> clients;
                }
    
                return $this -> respond($livres);
            } else {
                return $this -> failNotFound("Livre non trouvé");
            }
        }
    
        public function getLivresByClient(): \CodeIgniter\HTTP\ResponseInterface {
            $id = $this -> request -> getGet('id');
            $client = Client::with('livres.auteur', 'livres.serie') -> find($id);
    
            if ($client) {
                return $this -> respond($client -> livres);
            } else {
                return $this -> failNotFound("Client non trouvé");
            }
        }
    
        public function postLivre(): \CodeIgniter\HTTP\ResponseInterface {
            $items = $this -> request -> getRawInput();
    
            $titre = $items['tit'];
            $prix = $items['pri'];
            $categorie = $items['cat'];
            $isbn = $items['isb'];
            $auteur_id = $items['idA'];
    
            if (!$titre || !$prix || !$categorie || !$isbn || !$auteur_id) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $livre = new Livre();
    
                $livre -> titre = $titre;
                $livre -> prix = $prix;
                $livre -> categorie = $categorie;
                $livre -> isbn = $isbn;
                $livre -> auteur_id = $auteur_id;
    
                if ($items['idS']) {
                    $livre -> serie_id = $items['idS'];
                } else {
                    $livre -> serie_id = null;
                }
    
                if ($items['num']) {
                    $livre -> numero_ordre = $items['num'];
                } else {
                    $livre -> numero_ordre = null;
                }
    
                $result = $livre -> save();
    
                if ($result === 0) {
                    return $this -> fail("Erreur d'enregistrement");
                } else {
                    return $this -> respondCreated("Livre ajouté");
                }
            }
        }
    
        public function putLivre(): \CodeIgniter\HTTP\ResponseInterface {
            $items = $this -> request -> getRawInput();
    
            $id = $items['id'];
            $titre = $items['tit'];
            $prix = $items['pri'];
            $categorie = $items['cat'];
            $isbn = $items['isb'];
            $auteur_id = $items['idA'];
    
            if (!$id || !$titre || !$prix || !$categorie || !$isbn || !$auteur_id) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $livre = Livre::find($id);
    
                if (!$livre) {
                    return $this -> failNotFound("Le livre est inconnu");
                } else {
                    $livre -> id = $id;
                    $livre -> titre = $titre;
                    $livre -> prix = $prix;
                    $livre -> categorie = $categorie;
                    $livre -> isbn = $isbn;
                    $livre -> auteur_id = $auteur_id;
    
                    if ($items['idS']) {
                        $livre -> serie_id = $items['idS'];
                    } else {
                        $livre -> serie_id = null;
                    }
    
                    if ($items['num']) {
                        $livre -> numero_ordre = $items['num'];
                    } else {
                        $livre -> numero_ordre = null;
                    }
    
                    $result = $livre -> save();
    
                    if ($result === 0) {
                        return $this -> fail("Erreur d'enregistrement");
                    } else {
                        return $this -> respondCreated("Livre modifié");
                    }
                }
            }
        }
    
        public function deleteLivre(): \CodeIgniter\HTTP\ResponseInterface {
            $items = $this -> request -> getRawInput();
    
            $id = $items['id'];
    
            if (!$id) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $livre = Livre::find($id);
    
                if ($livre -> delete()) {
                    return $this -> respondDeleted("Livre supprimé");
                } else {
                    return $this -> failNotFound("Livre non trouvé");
                }
            }
        }

        /* ---------- AUTEURS ---------- */
    
        public function getAuteurs(): \CodeIgniter\HTTP\ResponseInterface {
            $auteurs = Auteur::all();
    
            if ($auteurs) {
                foreach ($auteurs as $auteur) {
                    $auteur -> livres;
                }
    
                return $this -> respond($auteurs);
            } else {
                return $this -> failNotFound("Auteur non trouvé");
            }
        }
    
        public function postAuteur(): \CodeIgniter\HTTP\ResponseInterface {
            $nom = $this -> request -> getPost('nom');
            $prenom = $this -> request -> getPost('pre');
            $biographie = $this -> request -> getPost('bio');
    
            if (!$nom || !$prenom || !$biographie) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $auteur = new Auteur();
    
                $auteur -> nom = $nom;
                $auteur -> prenom = $prenom;
                $auteur -> biographie = $biographie;
    
                $result = $auteur -> save();
    
                if ($result === 0) {
                    return $this -> fail("Erreur d'enregistrement");
                } else {
                    return $this -> respondCreated("Auteur ajouté");
                }
            }
        }
    
        public function putAuteur(): \CodeIgniter\HTTP\ResponseInterface {
            $inputs = $this -> request -> getRawInput();
    
            $id = $inputs['id'];
            $nom = $inputs['nom'];
            $prenom = $inputs['pre'];
            $biographie = $inputs['bio'];
    
            if (!$id || !$nom || !$prenom || !$biographie) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $auteur = Auteur::find($id);
    
                if ($auteur) {
                    $auteur -> nom = $nom;
                    $auteur -> prenom = $prenom;
                    $auteur -> biographie = $biographie;
    
                    $result = $auteur -> save();
    
                    if ($result === 0) {
                        return $this -> fail("Erreur d'enregistrement");
                    } else {
                        return $this -> respondCreated("Auteur modifié");
                    }
                } else {
                    return $this -> failNotFound("Auteur non trouvé");
                }
            }
        }
    
        public function deleteAuteur(): \CodeIgniter\HTTP\ResponseInterface {
            $inputs = $this -> request -> getRawInput();
    
            $id = $inputs['id'];
    
            if (!$id) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $auteur = Auteur::find($id);
    
                if ($auteur -> delete()) {
                    return $this -> respondDeleted("Auteur supprimé");
                } else {
                    return $this -> failNotFound("Auteur non trouvé");
                }
            }
        }

        /* ---------- SERIES ---------- */
    
        public function getSeries(): \CodeIgniter\HTTP\ResponseInterface {
            $series = Serie::all();
    
            foreach ($series as $serie) {
                $serie -> livres;
            }
    
            return $this -> respond($series);
        }
    
        public function getSerieById(): \CodeIgniter\HTTP\ResponseInterface {
            $id = $this -> request -> getGet('id');
    
            $serie = Serie::find($id);
    
            if ($serie) {
                $serie -> livres;
                return $this -> respond($serie);
            } else {
                return $this -> failNotFound("La série n'existe pas");
            }
        }

        public function putSerie() {
            $inputs = $this -> request -> getRawInput();

            $id = $inputs['id'];
            $libelle = $inputs['lib'];

            if (!$id || !$libelle) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $serie = Serie::find($id);
                if($serie) {
                    $serie -> libelle = $libelle;
                    $result = $serie -> save();
                    if($result === 0) {
                        return $this -> fail("Erreur d'enregistrement");
                    } else {
                        return $this -> respond("Enregistrement modifié");
                    }
                } else {
                    return $this -> fail("Série inconnue");
                }
            }
        }
    
        public function postSerie(): \CodeIgniter\HTTP\ResponseInterface {
            $items = $this -> request -> getRawInput();
    
            $libelle = $items['lib'];
    
            if (!$libelle) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $serie = new Serie();
                $serie -> libelle = $libelle;
    
                $result = $serie -> save();
    
                if ($result === 0) {
                    return $this -> fail("Erreur de l'enregistrement");
                } else {
                    return $this -> respondCreated("Série ajoutée");
                }
            }
        }

        public function deleteSerie() {
            $inputs = $this -> request -> getRawInput();

            $id = $inputs['id'];

            if (!$id) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $serie = Serie::find($id);

                if ($serie -> delete()) {
                    return $this -> respondDeleted("Série supprimée");
                } else {
                    return $this -> failNotFound("Série non trouvée");
                }
            }
        }

        /* ---------- CLIENTS ---------- */
    
        public function getClients(): \CodeIgniter\HTTP\ResponseInterface {
            $clients = Client::all();
    
            foreach ($clients as $client) {
                $client -> livres;
            }
    
            return $this -> respond($clients);
        }

        public function getClientById() {
            $id = $this -> request -> getGet('id');
            $client = Client::find($id);

            if ($client) {
                $client -> livres;

                return $this -> respond($client);
            } else {
                return $this -> failNotFound("Client non trouvé");
            }
        }

        public function getClientsByNom() {
            $nom = $this -> request -> getGet('nom');
            $clients = Client::where('nom', $nom) -> get();

            if ($clients) {
                foreach ($clients as $client) {
                    $client -> livres;
                }

                return $this -> respond($clients);
            } else {
                return $this -> failNotFound("Client non trouvé");
            }
        }

        public function postClient() {
            $prenom = $this -> request -> getPost('pre');
            $nom = $this -> request -> getPost('nom');

            if(!$nom || !$prenom) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $client = new Client();

                $client -> nom = $nom;
                $client -> prenom = $prenom;

                $result = $client -> save();
                if($result === 0){
                    return $this -> fail("Erreur d'enregistrement");
                } else {
                    return $this -> respondCreated("Client ajouté");
                }
            }
        }

        public function putClient() {
            $inputs = $this -> request -> getRawInput();

            $id = $inputs['id'];
            $nom = $inputs['nom'];
            $prenom = $inputs['pre'];

            if(!$id || !$nom || !$prenom) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $client = Client::find($id);
                if($client) {
                    $client -> nom = $nom;
                    $client -> prenom = $prenom;

                    $result = $client -> save();
                    if($result === 0){
                        return $this -> fail("Erreur d'enregistrement");
                    } else {
                        return $this -> respond("Enregistrement modifié");
                    }
                } else {
                    return $this -> fail("Client inconnu");
                }
            }
        }

        public function deleteClient() {
            $items = $this -> request -> getRawInput();

            $id = $items['id'];

            if (!$id) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $client = Client::find($id);

                if ($client -> delete()) {
                    return $this -> respondDeleted("Client supprimé");
                } else {
                    return $this -> failNotFound("Client non trouvé");
                }
            }
        }

        /* ---------- AVIS ---------- */

        public function getAvisByLivre() { // Retourne un tableau d’objets JSON comportant les avis et notes sur le livre dont l’id est donné en paramètre
            $id = $this -> request -> getGet('id');
            $livre = Livre::find($id);

            if($livre) {
                $livre -> clients;
                return $this -> respond($livre -> clients);
            } else {
                return $this -> failNotFound("Livre inconnu");
            }
        }

        public function postAvis() {
            $items = $this -> request -> getRawInput();

            $livre_id = $items['idL'];
            $client_id = $items['idC'];
            $note = $items['not'];
            $commentaire = $items['avi'];

            if(!$livre_id || !$client_id || !$note || !$commentaire) {
                return $this -> fail("Des informations sont manquantes");
            } else {
                $livre = Livre::find($livre_id);
                $client = Client::find($client_id);

                if($livre && $client) {
                        $livre -> clients() -> attach($client_id, ['note' => $note, 'avis' => $commentaire]);
                    return $this -> respondCreated("Avis ajouté");
                } else {
                    return $this -> failNotFound("Livre ou client inconnu");
                }
            }
        }
    }

?>
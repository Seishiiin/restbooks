<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Auteur extends Model {
        public $timestamps = false;

        public function livres() {
            return $this -> hasMany('App\Models\Livre');
        }
    }

?>
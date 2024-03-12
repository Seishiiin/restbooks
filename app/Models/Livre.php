<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Livre extends Model {
        public $timestamps = false;

        public function auteur() {
            return $this -> belongsTo('App\Models\Auteur');
        }

        public function serie() {
            return $this -> belongsTo('App\Models\Serie');
        }

        public function clients() {
            return $this -> belongsToMany('App\Models\Client') -> withPivot('note','avis');
        }
    }

?>
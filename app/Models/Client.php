<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Client extends Model {
        public $timestamps = false;

        public function livres() {
            return $this -> belongsToMany('App\Models\Livre') -> withPivot('note','avis');
        }
    }

?>
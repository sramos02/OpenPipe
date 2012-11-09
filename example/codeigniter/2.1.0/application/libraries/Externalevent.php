<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Calls an 'External system' for data
* An external system is a web service or database
*/
class Externalevent {
	const WEBSERVICE = 'webservice';
	const DATABASE = 'database';

	/**
	* Exectue a given external event
	* @param string $type
	* @param int $magnitude
	*/
	public function execute($type, $magnitude){
		switch ($type) {
			case self::WEBSERVICE:
				$this->webService($magnitude);
				break;
			
			case self::DATABASE:
				$this->database($magnitude);
				break;

			default:
				break;
		}
	}

	/**
	* Exectue a web service external event
	* @param int $magnitude
	*/
    public function webService($magnitude) {
    	$data = file_get_contents("http://search.twitter.com/search.json?q=blue%20angels&rpp=$magnitude&include_entities=true&result_type=mixed");
    	$data = json_decode($data, true);

    	foreach($data['results'] as $result){
    		$result['new_prop'] = true;
    	};

    	return $data;
    }

	/**
	* Exectue a database external event
	* @param int $magnitude
	*/
    public function database($magnitude) {
		$CI =& get_instance();
    	$query = $CI->db->query(
    		"select * from film".
    		" LEFT JOIN film_actor ON film.film_id = film_actor.film_id".
    		" LEFT JOIN actor ON film_actor.actor_id = actor.actor_id".
    		" LEFT JOIN inventory ON film.film_id = inventory.film_id".
    		" LEFT JOIN rental ON inventory.inventory_id = rental.inventory_id".
    		" LIMIT ".$magnitude*25
    	);

    	foreach ($query->result() as $row){
    		$row->new_prop = true;
    	}

    	return $query->result();
    }

}

/* End of file Externalevent.php */
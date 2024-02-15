<?php

namespace src;

use \Rabbit\Utils\Singleton;

class Configs extends Singleton {

	protected function __construct() {

	}

}

function configs(){
	return Configs::get();
}
configs();
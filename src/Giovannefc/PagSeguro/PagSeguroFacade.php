<?php
namespace Giovannefc\PagSeguro;

use Illuminate\Support\Facades\Facade;

class PagSeguroFacade extends Facade {

    protected static function getFacadeAccessor() { return 'pagseguro'; }

}
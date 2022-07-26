<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use TaylorNetwork\UsernameGenerator\Generator;
use Illuminate\Http\Request;
use DB;
class testing extends Controller
{
    public function test(){
        $generator = new Generator([  'case' => 'mixed' ]);
		dd($generator->generate('Some User'));

    }
}

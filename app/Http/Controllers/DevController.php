<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Filter;
use App\Models\Group;
use App\Services\User\GetUser;

use Auth;

class DevController extends Controller
{
    
    public function __construct(GetUser $getUser)
    {
        $this->getUser = $getUser;
    }

    public function dev()
    {
        $groups = Group::find(2)->descendantsAndSelf()->get();
        // s($groups);
        $users = $this->getUser->users_from_group( 1 );
        $users = $this->getUser->all();
        s(count($users));

        $groups = Filter::tree()->get();
        p($groups);
    }


}

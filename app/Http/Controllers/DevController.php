<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Filter;
use App\Models\Group;
use App\Services\User\ListUser;

use Auth;

class DevController extends Controller
{
    
    public function __construct(ListUser $listUser)
    {
        $this->listUser = $listUser;
    }

    public function dev()
    {
        $groups = Group::find(2)->descendantsAndSelf()->get();
        // s($groups);
        $users = $this->listUser->users_from_group( 1 );
        $users = $users = $this->listUser->all();
        s(count($users));

        $groups = Filter::tree()->get();
        p($groups);
    }


}

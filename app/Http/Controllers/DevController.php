<?php

namespace App\Http\Controllers;


use App\Models\Filter;
use App\Models\Group;
use App\Services\User\GetUser;


class DevController extends Controller
{
    private GetUser $getUser;
    
    public function __construct(GetUser $getUser)
    {
        $this->getUser = $getUser;
    }

    public function dev(): void
    {
        $groups = Group::find(2)->descendantsAndSelf()->get();
        // s($groups);
        $users = $this->getUser->users_from_group( 1 );
        $users = $this->getUser->all();
        if($users!=null) s(count($users));

        $groups = Filter::tree()->get();
        p($groups);
    }


}

<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Group;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToModel, WithValidation, WithStartRow, SkipsEmptyRows
{
    private $new_users = [];

    public function startRow(): int
    {
        return 2;
    }
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = new User([
            'name'     => $row[0],
            'email'    => $row[1],
            'password' => Hash::make(Str::random(32)),
            'group_id' => $this->_transformGroupColumn($row[2]),
            'is_group_admin' => (boolean) 0,
            'role' => 'author',
            'status_id' => (int) 20,
        ]);

        $this->new_users[] = $user;

        return $user;
    }
    
    public function prepareForValidation($row)
    {
        $row[0] = trim($row[0]);
        $row[1] = trim($row[1]);
        $row[2] = trim($row[2]);
        
        return $row;
    }

    private function _transformGroupColumn($value)
    {
        $group = Group::select('id')->where('name', '=', $value)->first();
        if(is_null($group)) return (int) 1;
        
        return (int) $group->id;
    }

    public function rules(): array
    {
        return [            
             '0' => 'required',
             '1' => 'required|email|unique:users,email',
             '2' => 'nullable|exists:groups,name'
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => __('user.errors.name.required'),
            '1.required' => __('user.errors.email.required'),
            '1.email' => __('user.errors.email.email', ['email'=>':input']),
            '1.unique' => __('user.errors.email.unique', ['email'=>':input'] ),
            '2.exists' => __('user.errors.group.exists', ['name'=>':input'] ),
        ];
    }

    public function getNewUsers(): array
    {
        return $this->new_users;
    }
}

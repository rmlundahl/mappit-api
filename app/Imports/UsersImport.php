<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithValidation, WithStartRow
{

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
        return new User([
            'name'     => $row[0],
            'email'    => $row[1],
            'password' => Hash::make($row[1]),
            'group_id' => $this->_transformGroupColumn($row[2]),
            'is_group_admin' => (boolean) 0,
            'role' => 'authors',
        ]);
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
            '1.unique' => __('user.errors.email.unique', ['email'=>':input'] ),
            '2.exists' => __('user.errors.group.exists', ['name'=>':input'] ),
        ];
    }
}

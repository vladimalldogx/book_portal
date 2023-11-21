<?php

namespace App\Imports;
use App\Helpers\HumanNameFormatterHelper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\User;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
       /**
        *adding by bulk  

        */
        $formattedName = (new HumanNameFormatterHelper)->parse($row['employeename']);
        $user = User::where('firstname', $formattedName->FIRSTNAME)->where('lastname', $formattedName->LASTNAME)->get();
            if(count($user) == 0){
               return new User([
                    'firstname' => $formattedName->FIRSTNAME,
                    'lastname' => $formattedName->LASTNAME,
                   // 'middlename'=>$formattedName->MIDDLEINITIAL,
                    'usertype'=> $row['usertype_number'],
                    'email'=> $row['username'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($row['password'])
               ]);
            }
    }
    public function headingRow(): int
    {
        return 1;
    }
}

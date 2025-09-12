<?php

namespace App\Repository;

use App\Models\User;
use App\Models\Stallowner;
use App\Models\StallOwnerEmp;
use Illuminate\Http\Response;
use App\Models\StallOwnerChild;
use App\Models\StallOwnerFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interface\Repository\FileRepositoryInterface;
use App\Interface\Repository\StallOwnerRepositoryInterface;

class StallOwnerRepository implements StallOwnerRepositoryInterface
{
    public function findMany(object $payload)
    {
        // $query = DB::table('stallowner as a')
        //     ->where('a.ownerStatus', 'ACTIVE')
        //     ->when($payload->name, function ($q) use ($payload) {
        //         $fullname = trim(preg_replace('/\s+/', ' ', $payload->name));
        //         $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
        //     });
        return Stallowner::with(['children', 'employees', 'files'])
            ->filter(request()->all())
            ->orderBy('stallOwnerId', 'desc')
            ->paginate(10);

        // return $query->paginate(10);
    }

    public function create(object $payload)
    {
        // $user = new User();
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function show(string $id)
    {
        // $user = User::findOrFail($id);
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function update(object $payload, string $id)
    {
        // $user = User::findOrFail($id);
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function delete(string $id)
    {
        $file = StallOwnerFiles::findOrFail($id);
        return $file->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }
}

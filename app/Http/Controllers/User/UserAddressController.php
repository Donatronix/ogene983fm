<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressStoreRequest;
use App\Models\Address\Address;
use App\Traits\ControllerTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAddressController extends Controller
{
    use ControllerTrait;

    /**
     * display form for creating address for user resource
     *
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create(User $user)
    {
        $addressView = view('site.dashboard.user.address.create', ['user' => $user])->render();
        return response()->json(['modal' => $addressView], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AddressStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressStoreRequest $request, User $user)
    {
        $request->flash();

        // Will return only validated data
        $request->validated();
        DB::beginTransaction();
        try {
            $user = User::findOrFail($user->id);
            $user->saveAddress($request);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 200);
        }
        DB::commit();
        return response()->json(['message' => 'Address was added successfully!'], 200);
    }


    public function edit(Address $address)
    {
        $addressView = view('site.dashboard.user.address.edit', ['address' => $address])->render();
        return response()->json(['modal' => $addressView], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AddressStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(AddressStoreRequest $request, User $user, Address $address)
    {
        $request->flash();

        // Will return only validated data
        $request->validated();
        DB::beginTransaction();
        try {
            $user = User::findOrFail($user->id);
            $user->updateAddress($request, $address);
            $user->updateAddress($request, $address);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', 'Address was updated successfully!');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\User\User $user
     * @param \App\Models\Address\User $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Address $address)
    {
        DB::beginTransaction();
        try {
            $user->deleteAddress($address);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', 'Address was deleted successfully!');
        return back();
    }
}

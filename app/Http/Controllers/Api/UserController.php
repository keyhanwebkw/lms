<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserTypes;
use App\Http\Requests\Api\User\CreateChildRequest;
use App\Http\Requests\Api\User\DeleteChildRequest;
use App\Http\Requests\Api\User\GetChildRequest;
use App\Http\Requests\Api\User\SetProfileRequest;
use App\Http\Requests\Api\User\switchToChildRequest;
use App\Http\Requests\Api\User\UpdateChildRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Resources\ChildProfileResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Keyhanweb\Subsystem\Models\Storage;

class UserController extends ApiController
{
    /**
     * @param SetProfileRequest $request
     * @return JsonResponse
     */
    public function setProfile(SetProfileRequest $request)
    {
        $data = $request->validated();
        $authUser = Auth::user();

        try {
            $storage = Storage::validate([
                'SID' => $data['avatarSID'],
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        if ($authUser->status != UserStatus::WaitingForSetProfile->value) {
            return $this->error(1, st('You are not allowed to edit profile information'));
        }

        $authUser->fill($data);
        $authUser->status = UserStatus::Active->value;
        $authUser->save();

        if ( !$storage->isPublic) {
            $storage->used($authUser);
        }

        return $this->success([
            'profile' => UserProfileResource::make($authUser),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getProfile()
    {
        return $this->success([
            'profile' => UserProfileResource::make(Auth::user()),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function listChild()
    {
        $parentID = Auth::ID();

        $checkChild = User::query()
            ->where('parentID', $parentID)
            ->where('type', UserTypes::Child)
            ->get();

        return $this->success([
            'children' => ChildProfileResource::collection($checkChild),
        ]);
    }

    /**
     * @param CreateChildRequest $request
     * @return JsonResponse
     */
    public function createChild(CreateChildRequest $request)
    {
        $data = $request->validated();
        $parent = Auth::user();

        $checkChild = User::query()
            ->where('parentID', $parent->ID)
            ->where('name', $data['name'])
            ->where('type', UserTypes::Child)
            ->exists();
        if ($checkChild) {
            return $this->error(1, st('A child with these details is already registered in the system'));
        }

        if (empty($data['username'])) {
            $username = User::generateUsername();
        } else {
            $username = $data['username'];
        }

        $child = User::query()
            ->create([
                'name' => $data['name'],
                'gender' => $data['gender'],
                'type' => UserTypes::Child->value,
                'parentID' => $parent->ID,
                'nationalCode' => $data['nationalCode'] ?? null,
                'birthDate' => $data['birthDate'] ?? null,
                'username' => $username,
                'registerDate' => time(),
                'lastActivity' => time(),
                'avatarSID' => $data['avatarSID'],
            ]);

        return $this->success([
            'child' => ChildProfileResource::make($child),
        ]);
    }

    /**
     * @param GetChildRequest $request
     * @return JsonResponse
     */
    public function getChild(GetChildRequest $request)
    {
        $data = $request->validated();
        $parentID = Auth::ID();
        $child = User::query()
            ->where('ID', $data['childID'])
            ->where('parentID', $parentID)
            ->where('type', UserTypes::Child)
            ->first();

        if ( !$child) {
            return $this->error(1, st('record not found'));
        }

        return $this->success([
            'child' => ChildProfileResource::make($child),
        ]);
    }

    /**
     * @param switchToChildRequest $request
     * @return JsonResponse
     */
    public function switchChild(switchToChildRequest $request)
    {
        $data = $request->validated();
        $parentID = Auth::ID();

        $child = User::query()
            ->where('ID', $data['childID'])
            ->where('parentID', $parentID)
            ->where('type', UserTypes::Child)
            ->first();

        if ( !$child) {
            return $this->error(1, st('record not found'));
        }

        $newToken = $child->createToken('childToken')->plainTextToken;

        return $this->success([
            'child' => ChildProfileResource::make($child),
            'token' => $newToken,
        ]);
    }

    /**
     * @param DeleteChildRequest $request
     * @return JsonResponse
     */
    public function deleteChild(DeleteChildRequest $request)
    {
        $data = $request->validated();
        $parentID = Auth::ID();

        $child = User::query()
            ->where('ID', $data['childID'])
            ->where('parentID', $parentID)
            ->where('type', UserTypes::Child)
            ->first();

        if ( !$child) {
            return $this->error(1, st('record not found'));
        }

        $child->markAsDeleted();

        return $this->success();
    }

    /**
     * @param UpdateChildRequest $request
     * @return JsonResponse
     */
    public function updateChild(UpdateChildRequest $request): JsonResponse
    {
        $data = $request->validated();
        $parent = Auth::user();

        $child = User::query()
            ->where('ID', $data['childID'])
            ->where('parentID', $parent->ID)
            ->where('type', UserTypes::Child)
            ->first();
        if ( !$child) {
            return $this->error(1, st('record not found'));
        }

        if (strtolower($child->name) !== strtolower($data['name'])) {
            $isChildExistedWithSameName = User::query()
                ->where('ID', '!=', $child->ID)
                ->where('parentID', $parent->ID)
                ->where('name', $data['name'])
                ->where('type', UserTypes::Child)
                ->exists();
            if ($isChildExistedWithSameName) {
                return $this->error(2, st('A child with these details is already registered in the system'));
            }
        }

        $child->update($data);

        return $this->success([
            'child' => ChildProfileResource::make($child),
        ]);
    }

    /**
     * @return JsonResponse
     * @param UpdateProfileRequest $request
     * @link https://docs.google.com/document/d/1NtW3jXeYIoRqdGGsCdRiD7DZifXyCCWylUZiSdc6ll4/edit?tab=t.0
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        $authUser = Auth::user();

        if ($authUser->status != UserStatus::Active->value) {
            return $this->error(1, st('You are not allowed to edit profile information'));
        }

        $authUser->update($data);

        return $this->success([
            'profile' => UserProfileResource::make($authUser),
        ]);
    }

}

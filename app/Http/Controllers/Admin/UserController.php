<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ChildDatatable;
use App\DataTables\ParentDatatable;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateChildRequest;
use App\Http\Requests\Admin\User\CreateParentRequest;
use App\Http\Requests\Admin\User\UpdateChildRequest;
use App\Http\Requests\Admin\User\UpdateParentRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Keyhanweb\Subsystem\Enums\Gender;
use Keyhanweb\Subsystem\Enums\UserStatus;
use Keyhanweb\Subsystem\Models\Role;
use Keyhanweb\Subsystem\Models\Storage;
use Keyhanweb\Subsystem\Models\UserInfo;
use Keyhanweb\Subsystem\Models\UserRole;

class UserController extends Controller
{

    /**
     * @param ParentDatatable $datatable
     * @return mixed
     */
    public function listParent(ParentDatatable $datatable)
    {
        $statuses = UserStatus::valuesTranslate();
        $roles = Role::query()
            ->pluck('name', 'ID');

        return $datatable->render('admin.user.parent.list', compact('statuses', 'roles'));
    }

    /**
     * @return View
     */
    public function createParent()
    {
        $genders = Gender::valuesTranslate();
        $roles = Role::query()
            ->pluck('name', 'ID');

        return view('admin.user.parent.create', compact('genders', 'roles'));
    }

    /**
     * @param CreateParentRequest $request
     * @return RedirectResponse
     */
    public function storeParent(CreateParentRequest $request)
    {
        $data = $request->validated();
        $roles = $data['roles'];
        $biography = $data['biography'] ?? null;

        // User Info
        $socialMedia = isset($data['socialMedia']) ? $this->strToJson($data['socialMedia']) : null;
        $extraInfo = isset($data['extraInfo']) ? $this->strToJson($data['extraInfo']) : null;
        if ($extraInfo === false || $socialMedia === false) {
            return back()->withInput()->withErrors(st('Json format error'));
        }
        $pictureSID = null;
        if (isset($data['picture'])) {
            $storage = Storage::uploadFile(['file' => $data['picture'], 'type' => 'image']);
            $pictureSID = $storage->SID;
        }

        unset($data['biography'], $data['picture'], $data['socialMedia'], $data['extraInfo'], $data['roles']);

        $data['password'] = Hash::make($data['password']);
        $data += [
            'status' => UserStatus::Active->value,
            'registerDate' => time(),
            'lastActivity' => time()
        ];

        $user = User::create($data);

        $insertQueryData = [];
        foreach ($roles as $role) {
            $insertQueryData[] = [
                'userID' => $user->ID,
                'roleID' => $role,
            ];
        }
        UserRole::insert($insertQueryData);


        UserInfo::create([
            'userID' => $user->ID,
            'socialMedia' => $socialMedia,
            'extraInfo' => $extraInfo,
            'biography' => $biography,
            'pictureSID' => $pictureSID,
        ]);

        if ($pictureSID) {
            $storage->used($user, true);
        }

        return back()->with('success', st('Operation done successfully'));
    }

    /**
     * @param string $str
     * @return false|string
     * @pattern key=value#key2=value2#...
     */
    public function strToJson(string $str): string|false
    {
        try {
            $pairs = explode('#', $str);
            $pairs = array_filter($pairs);

            $result = [];
            foreach ($pairs as $pair) {
                [$key, $value] = explode('=', $pair);
                $result[$key] = $value;
            }
        } catch (Exception $e) {
            return false;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param User $parent
     * @return View
     */
    public function showParent(User $parent)
    {
        $children = User::query()
            ->where('parentID', $parent->ID)
            ->pluck('name')
            ->implode(', ');

        $roleIDs = UserRole::query()
            ->where('userID', $parent->ID)
            ->pluck('roleID')
            ->toArray();

        $roles = Role::query()
            ->whereIn('ID', $roleIDs)
            ->pluck('name')
            ->implode(' ,');

        return view(
            'admin.user.parent.show',
            compact('children', 'parent', 'roles')
        );
    }

    /**
     * @param User $parent
     * @return View
     */
    public function editParent(User $parent)
    {
        $statuses = UserStatus::valuesTranslate();
        $genders = Gender::valuesTranslate();
        $roles = Role::query()
            ->pluck('name', 'ID');
        $selectedRoles = UserRole::query()
            ->where('userID', $parent->ID)
            ->pluck('roleID');

        $userInfo = UserInfo::query()
            ->where('userID', $parent->ID)
            ->first();

        $socialMedia = isset($userInfo->socialMedia) ? $this->jsonToStr($userInfo->socialMedia) : null;
        $extraInfo = isset($userInfo->extraInfo) ? $this->jsonToStr($userInfo->extraInfo) : null;
        $biography = $userInfo?->biography;
        $picturePath = null;

        if (isset($userInfo->pictureSID)) {
            $storage = Storage::findBySID($userInfo->pictureSID);
            $picturePath = route('storage.download', ['SID' => $userInfo->pictureSID, 'type' => 'original']) . '.' . $storage->extension;
        }

        return view(
            'admin.user.parent.edit',
            compact(
                'statuses',
                'genders',
                'parent',
                'roles',
                'selectedRoles',
                'socialMedia',
                'extraInfo',
                'biography',
                'picturePath'
            )
        );
    }

    /**
     * @param string $json
     * @return false|string
     * @pattern key=value#key2=value2#...
     */
    public function jsonToStr(string $json): false|string
    {
        try {
            $data = json_decode($json, true);
            $pairs = [];

            foreach ($data as $key => $value) {
                $pairs[] = "{$key}={$value}";
            }

            return implode('#', $pairs) . '#';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param UpdateParentRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateParent(UpdateParentRequest $request, User $user)
    {
        $data = $request->validated();
        $userInfo = UserInfo::query()
            ->where('userID', $user->ID)
            ->first();
        $biography = $data['biography'] ?? $userInfo?->biography;

        // User Info
        $socialMedia = isset($data['socialMedia']) ? $this->strToJson($data['socialMedia']) : $userInfo?->socialMedia;
        $extraInfo = isset($data['extraInfo']) ? $this->strToJson($data['extraInfo']) : $userInfo?->extraInfo;
        if ($extraInfo === false || $socialMedia === false) {
            return back()->withInput()->withErrors(st('Json format error'));
        }

        $pictureSID = null;
        if (isset($data['picture'])) {
            !$userInfo ?: Storage::deleteBySID($userInfo->pictureSID);

            $storage = Storage::uploadFile(['file' => $data['picture'], 'type' => 'image']);
            $pictureSID = $storage->SID;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $currentRoles = UserRole::query()
            ->where('userID', $user->ID)
            ->pluck('roleID')
            ->toArray();

        if (isset($data['roles'])) {
            $newRoles = array_map('intval', $data['roles']);

            if (json_encode($currentRoles) !== json_encode($newRoles)) {
                UserRole::query()
                    ->where('userID', $user->ID)
                    ->delete();

                $insertQueryData = [];
                foreach ($newRoles as $role) {
                    $insertQueryData[] = [
                        'userID' => $user->ID,
                        'roleID' => $role,
                    ];
                }
                UserRole::insert($insertQueryData);
            }
        }

        unset($data['biography'], $data['picture'], $data['socialMedia'], $data['extraInfo'], $data['roles']);

        $user->fill($data);
        $user->save();

        if ($userInfo) {
            $userInfo->update([
                'socialMedia' => $socialMedia,
                'extraInfo' => $extraInfo,
                'biography' => $biography,
                'pictureSID' => $pictureSID ?? $userInfo->pictureSID,
            ]);
        } else {
            UserInfo::query()
                ->create([
                    'userID' => $user->ID,
                    'socialMedia' => $socialMedia,
                    'extraInfo' => $extraInfo,
                    'biography' => $biography,
                    'pictureSID' => $pictureSID,
                ]);
        }

        if ($pictureSID) {
            $storage->used($userInfo, true);
        }

        return redirect()->route('admin.user.parent.list')->with('success', st('Operation done successfully'));
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function delete(User $user)
    {
        $user->markAsDeleted();
        $user->save();

        return back()->with('success', st('Operation done successfully'));
    }

    /**
     * @param ChildDatatable $datatable
     * @return mixed
     */
    public function listChild(ChildDatatable $datatable)
    {
        $statuses = UserStatus::valuesTranslate();

        return $datatable->render('admin.user.child.list', compact('statuses'));
    }

    /**
     * @return View
     */
    public function createChild()
    {
        $parents = User::query()
            ->where('type', UserTypes::Parent->value)
            ->where('status', UserStatus::Active->value)
            ->get()->pluck('fullname', 'ID')->toArray();

        $genders = Gender::valuesTranslate();

        return view('admin.user.child.create', compact('parents', 'genders'));
    }

    /**
     * @param CreateChildRequest $request
     * @return RedirectResponse
     */
    public function storeChild(CreateChildRequest $request)
    {
        $data = $request->validated();

        $checkChild = User::query()
            ->where('parentID', $data['parentID'])
            ->where('name', $data['name'])
            ->exists();
        if ($checkChild) {
            return back()->withErrors(
                st('A child with these details is already registered in the system')
            );
        }

        if (empty($data['username'])) {
            $data['username'] = User::generateUsername();
        }
        $data += [
            'type' => UserTypes::Child->value,
            'status' => UserStatus::Active->value,
            'registerDate' => time(),
            'lastActivity' => time(),
        ];

        User::create($data);

        return back()->with('success', st('Operation done successfully'));
    }

    /**
     * @param User $child
     * @return View
     */
    public function showChild(User $child)
    {
        return view('admin.user.child.show', compact('child'));
    }

    /**
     * @param User $child
     * @return View
     */
    public function editChild(User $child)
    {
        $parents = User::query()
            ->where('type', UserTypes::Parent->value)
            ->where('status', UserStatus::Active->value)
            ->get()->pluck('fullname', 'ID')->toArray();

        $statuses = UserStatus::valuesTranslate();
        $genders = Gender::valuesTranslate();

        return view(
            'admin.user.child.edit',
            compact('statuses', 'parents', 'genders', 'child')
        );
    }

    /**
     * @param UpdateChildRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateChild(UpdateChildRequest $request, User $user)
    {
        $data = $request->validated();

        if (strtolower($user->name) !== strtolower($data['name'])) {
            $checkChild = User::query()
                ->where('parentID', $data['parentID'])
                ->where('name', $data['name'])
                ->exists();
            if ($checkChild) {
                return back()->withErrors(
                    st('A child with these details is already registered in the system')
                );
            }
        }

        $user->fill($data);
        $user->save();

        return redirect()->route('admin.user.child.list')->with('success', st('Operation done successfully'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Setting\HomeBannersRequest;
use App\Http\Requests\Admin\Setting\LatestArticlesRequest;
use App\Http\Requests\Admin\Setting\LatestCoursesRequest;
use App\Http\Requests\Admin\Setting\QuickAccessesRequest;
use App\Models\Course;
use App\Models\Setting;
use Hekmatinasser\Verta\Verta;
use Keyhanweb\Subsystem\Http\Controllers\Web\Controller;
use Keyhanweb\Subsystem\Models\Article;
use Keyhanweb\Subsystem\Models\Storage;
use Hekmatinasser\Jalali\Exceptions\InvalidDatetimeException;

class SettingController extends Controller
{
    public function indexPage()
    {
        $settings = [];

        $relatedSettings = Setting::query()
            ->where('relatedTo', 'indexPage')
            ->get();

        foreach ($relatedSettings as $item) {
            $settings[] = ['name' => st($item->key), 'routeName' => 'admin.setting.indexPage.' . $item->key . '.show'];
        }

        return view('admin.setting.indexPage', compact('settings'));
    }

    public function homeBannersShow()
    {
        $settings = Setting::query()
            ->where('key', 'homeBanners')
            ->first();
        $values = array_pad($settings->value, $settings->limit, null);

        foreach ($values as &$value) {
            if (isset($value['bannerSID'])) {
                $storage = Storage::findBySID($value['bannerSID']);
                $bannerUrl = Storage::getStorageUrl($storage);
                $value['bannerUrl'] = $bannerUrl;
            }
        }
        unset($value);

        return view('admin.setting.homeBanners', compact('settings', 'values'));
    }

    public function homeBannersSet(HomeBannersRequest $request)
    {
        $data = $request->validated();
        $homeBanners = $data['homeBanners'];

        $setting = Setting::query()
            ->where('key', 'homeBanners')
            ->first();

        foreach ($data['homeBanners'] as $index => $banner) {
            if (isset($banner['banner'])) {
                $storage = Storage::uploadFile(['file' => $banner['banner'], 'type' => 'image']);
                $storage->used($setting, true);
                if (isset($homeBanners[$index]['bannerSID'])) {
                    Storage::deleteBySID($homeBanners[$index]['bannerSID']);
                }

                $homeBanners[$index]['bannerSID'] = $storage->SID;
                unset($homeBanners[$index]['banner']);
            }
            // If type be anything instead of integer, it means the date is not Unix timestamp
            if (!is_int($banner['startDisplayDate']) || !is_int($banner['endDisplayDate'])) {
                try {
                    $homeBanners[$index]['startDisplayDate'] = Verta::parse($banner['startDisplayDate'])->timestamp;
                    $homeBanners[$index]['endDisplayDate'] = Verta::parse($banner['endDisplayDate'])->timestamp;
                } catch (InvalidDatetimeException $e){
                    return back()->withErrors(st('Time conversion error') . ' - ' . st('Segment', ['number' => $index + 1]));
                }
            }
        }

        $value = array_pad($homeBanners, $setting->limit, []);
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        $setting->value = $value;
        $setting->updated = time();
        $setting->save();

        return redirect()->route('admin.setting.indexPage')->with('success', st('Operation done successfully'));
    }

    public function latestArticlesShow()
    {
        $setting = Setting::query()
            ->where('key', 'homeLatestArticles')
            ->first();

        $articles = Article::query()
            ->select(
                'ID',
                'title',
            )
            ->pluck('title', 'ID');

        $values = array_pad($setting->value, $setting->limit, null);

        return view('admin.setting.latestArticles', compact('setting', 'values', 'articles'));
    }

    public function latestArticlesSet(LatestArticlesRequest $request)
    {
        $data = $request->validated();
        $setting = Setting::query()
            ->where('key', 'homeLatestArticles')
            ->first();

        $value = array_pad($data['homeLatestArticles'], $setting->limit, []);
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);

        $setting->value = $value;
        $setting->updated = time();
        $setting->save();

        return redirect()->route('admin.setting.indexPage')->with('success', st('Operation done successfully'));
    }

    public function latestCoursesShow()
    {
        $setting = Setting::query()
            ->where('key', 'homeLatestCourses')
            ->first();

        $courses = Course::query()
            ->select(
                'ID',
                'name',
            )
            ->pluck('name', 'ID');

        $values = array_pad($setting->value, $setting->limit, null);

        return view('admin.setting.latestCourses', compact('setting', 'values', 'courses'));
    }

    public function latestCoursesSet(LatestCoursesRequest $request)
    {
        $data = $request->validated();
        $setting = Setting::query()
            ->where('key', 'homeLatestCourses')
            ->first();

        $value = array_pad($data['homeLatestCourses'], $setting->limit, []);
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);

        $setting->value = $value;
        $setting->updated = time();
        $setting->save();

        return redirect()->route('admin.setting.indexPage')->with('success', st('Operation done successfully'));
    }

    public function homeContent()
    {
        $settings = Setting::query()
            ->where('key', 'homeContent')
            ->first();
        $values = array_pad($settings->value, $settings->limit, null);

        foreach ($values as &$value) {
            if (isset($value['iconSID'])) {
                $storage = Storage::findBySID($value['iconSID']);
                $iconUrl = Storage::getStorageUrl($storage);
                $value['iconUrl'] = $iconUrl;
            }
        }
        unset($value);

        return view('admin.setting.homeContent', compact('settings', 'values'));
    }

    public function homeContentSet(QuickAccessesRequest $request)
    {
        $data = $request->validated();
        $quickAccesses = $data['quickAccesses'];

        $setting = Setting::query()
            ->where('key', 'homeQuickAccesses')
            ->first();

        foreach ($data['quickAccesses'] as $index => $item) {
            if (isset($item['icon'])) {
                $storage = Storage::uploadFile(['file' => $item['icon'], 'type' => 'image']);
                $storage->used($setting, true);
                if (isset($quickAccesses[$index]['iconSID'])) {
                    Storage::deleteBySID($quickAccesses[$index]['iconSID']);
                }

                $quickAccesses[$index]['iconSID'] = $storage->SID;
                unset($quickAccesses[$index]['icon']);
            }
        }

        $value = array_pad($quickAccesses, $setting->limit, []);
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        $setting->value = $value;
        $setting->updated = time();
        $setting->save();

        return redirect()->route('admin.setting.indexPage')->with('success', st('Operation done successfully'));
    }
}

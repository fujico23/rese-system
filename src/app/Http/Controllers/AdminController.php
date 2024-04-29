<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Shop;
use App\Models\ShopUser;


class AdminController extends Controller
{
    public function admin()
    {
        $users = User::with('role')->get();
        return view('admin', compact('users'));
    }

    public function show(User $user)
    {
        $roles = Role::all();
        $shops = Shop::all();
        $userRoleId = $user->role_id;
        $shopUsers = ShopUser::where('user_id', $user->id)->get();
        return view('user_details', compact('shopUsers', 'user', 'roles', 'shops', 'userRoleId'));
    }

    public function update(Request $request, User $user)
    {
        //role権限を変更
        $user->update(['role_id' => $request->role_id]);
        //もしrole_idが2→3に降格したらshop_usersテーブルから関連レコード削除
        if ($request->role_id == 3) {
            $user->shopUsers()->delete();
        }
        return redirect()->route('users.show', $user)->with('success', 'ユーザーの権限を変更しました');
    }

    public function store(Request $request, User $user)
    {
        // リクエストからshop_idを取得する
        $shopId = $request->input('shop_id');
        // 同じ組み合わせが既に存在するかチェックする
        $existingShopUser = ShopUser::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->first();

        //同じ組み合わせがあったら保存しない。なければデータを保存する。
        if ($existingShopUser) {
            return redirect()->back()->with('error', '既にこの店舗の代表者になっています');
        }
        ShopUser::create([
            'user_id' => $user->id,
            'shop_id' => $shopId,
        ]);
        return redirect()->route('users.show', ['user' => $user])->with('success', '選択した店舗の代表者の権限を与えました');
    }

    public function remove(Request $request, User $user)
    {
        //user_idとshop_idが一致しているレコードを削除する処理
        $shopId = $request->input('shop_id');
        ShopUser::where('user_id', $user->id)->where('shop_id', $shopId)->delete();
        return redirect()->back()->with('success', '選択した店舗の代表者権限が削除されました');
    }


}

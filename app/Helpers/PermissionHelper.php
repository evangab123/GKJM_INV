<?php

namespace App\Helpers;

use App\Models\Pengguna;
use App\Models\Ruang;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionHelper
{
    public static function getRoomList()
    {
        $ruangs = Ruang::all();
        $permruangs = ['semua'];

        // Add all room names to the list
        foreach ($ruangs as $room) {
            $permruangs[] = $room->nama_ruang;
        }

        return $permruangs;
    }
    public static function userHasPermission($permission)
    {
        $pengguna = Auth::user();

        if (!$pengguna) {
            return false;
        }
        $userPermissions = $pengguna->permissions->pluck('name')->toArray();

        return in_array($permission, $userPermissions);
    }



    public static function AnyHasAccessToBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }

        $permruangs = self::getRoomList();
        $hasAccess = false;
        $ruangs = [];

        foreach ($permruangs as $room) {
            if (
                self::userHasPermission('lihat-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-semua-' . strtolower($room))
            ) {
                $hasAccess = true;
                $ruangs[] = $room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['access' => $hasAccess, 'room' => $ruangs];
    }


    public static function AnyCanCreateBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canMake = false;
        $ruangs = [];
        foreach ($permruangs as $room) {
            if (
                self::userHasPermission('buat-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-semua-' . strtolower($room))
            ) {
                $canMake = true;
                $ruangs[] = $room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['buat' => $canMake, 'room' => $ruangs];
    }
    public static function AnyCanEditBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canEdit = false;
        $ruangs = [];

        foreach ($permruangs as $room) {
            if (
                self::userHasPermission('perbarui-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-semua-' . strtolower($room))
            ) {
                $canEdit = true;
                $ruangs[] = $room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['edit' => $canEdit, 'room' => $ruangs];
    }

    public static function AnyCanDeleteBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canDelete = false;
        $ruangs = [];

        foreach ($permruangs as $room) {
            if (
                self::userHasPermission('hapus-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-barang-' . strtolower($room)) ||
                self::userHasPermission('semua-semua-' . strtolower($room))
            ) {
                $canDelete = true;
                $ruangs[] = $room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['delete' => $canDelete, 'room' => $ruangs];
    }

    public static function AnyCanAccessPenghapusan()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }

        $permruangs = self::getRoomList();
        $canAccess = false;
        $ruangs = [];

        foreach ($permruangs as $room) {
            if (
                self::userHasPermission('lihat-penghapusan-' . strtolower($room)) ||
                self::userHasPermission('semua-penghapusan-' . strtolower($room)) ||
                self::userHasPermission('semua-semua-' . strtolower($room))
            ) {
                $canAccess = true;
                $ruangs[] = $room;
            }
        }

        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['access' => $canAccess, 'room' => $ruangs];
    }

    public static function AnyCanDeletePenghapusan()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }

        $permruangs = self::getRoomList();
        $canDelete = false;
        $ruangs = [];

        foreach ($permruangs as $room) {
            if (
                self::userHasPermission('hapus-penghapusan-' . strtolower($room)) ||
                self::userHasPermission('semua-penghapusan-' . strtolower($room)) ||
                self::userHasPermission('semua-semua-' . strtolower($room))
            ) {
                $canDelete = true;
                $ruangs[] = $room;
            }
        }

        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['delete' => $canDelete, 'room' => $ruangs];
    }
}

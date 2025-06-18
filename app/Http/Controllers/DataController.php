<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandPerangkat;
use App\Models\JenisPerangkat;
use App\Models\BrandFasilitas;
use App\Models\JenisFasilitas;
use App\Models\BrandAlatukur;
use App\Models\JenisAlatukur;
use App\Models\Region;
use App\Models\Site;
use App\Models\User;
use App\Models\TipeJaringan;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Hash;

class DataController extends Controller
{
    public function index()
    {
        return view('menu.data.data');
    }

    // ------------------------------- PERANGKAT -------------------------------

    public function indexPerangkat()
    {
        $brandperangkat = BrandPerangkat::all();
        $jenisperangkat = JenisPerangkat::all();
        return view('menu.data.dataperangkat', compact('brandperangkat', 'jenisperangkat'));
    }

    public function storeBrandPerangkat(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandperangkat,kode_brand',
        ]);

        BrandPerangkat::create($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function editBrandPerangkat($kode_brand)
    {
        $brandPerangkat = BrandPerangkat::findOrFail($kode_brand);
        return view('brandperangkat.edit', compact('brandPerangkat'));
    }


    public function updateBrandPerangkat(Request $request, $kode_brand)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandperangkat,kode_brand,' . $kode_brand . ',kode_brand',
        ]);

        $brandPerangkat = BrandPerangkat::findOrFail($kode_brand);
        $brandPerangkat->update($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil diupdate.');
    }

    public function destroyBrandPerangkat($kode_brand)
    {
        $brandPerangkat = BrandPerangkat::findOrFail($kode_brand);
        $brandPerangkat->delete();

        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil dihapus.');
    }

    public function createJenisPerangkat()
    {
        return view('jenisperangkat.create');
    }

    public function storeJenisPerangkat(Request $request)
    {
        $request->validate([
            'nama_perangkat' => 'required',
            'kode_perangkat' => 'required|unique:jenisperangkat,kode_perangkat',
        ]);

        JenisPerangkat::create($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Jenis berhasil ditambahkan.');
    }

    public function editJenisPerangkat($kode_perangkat)
    {
        $jenisPerangkat = JenisPerangkat::findOrFail($kode_perangkat);
        return view('jenisperangkat.edit', compact('jenisPerangkat'));
    }


    public function updateJenisPerangkat(Request $request, $kode_perangkat)
    {
        $request->validate([
            'nama_perangkat' => 'required',
            'kode_perangkat' => 'required|unique:jenisperangkat,kode_perangkat,' . $kode_perangkat . ',kode_perangkat',
        ]);

        $jenisPerangkat = JenisPerangkat::findOrFail($kode_perangkat);
        $jenisPerangkat->update($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Jenis berhasil diupdate.');
    }

    public function destroyJenisPerangkat($kode_perangkat)
    {
        $jenisPerangkat = JenisPerangkat::findOrFail($kode_perangkat);
        $jenisPerangkat->delete();

        return redirect()->route('dataperangkat.index')->with('success', 'Jenis berhasil dihapus.');
    }

    // ------------------------------- FASILITAS -------------------------------

    public function indexFasilitas()
    {
        $brandfasilitas = BrandFasilitas::all();
        $jenisfasilitas = JenisFasilitas::all();
        return view('menu.data.datafasilitas', compact('brandfasilitas', 'jenisfasilitas'));
    }
    public function createBrandFasilitas()
    {
        return view('brandfasilitas.create');
    }

    public function storeBrandFasilitas(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandfasilitas,kode_brand',
        ]);

        BrandFasilitas::create($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function editBrandFasilitas($kode_brand)
    {
        $brandFasilitas = BrandFasilitas::findOrFail($kode_brand);
        return view('brandfasilitas.edit', compact('brandFasilitas'));
    }


    public function updateBrandFasilitas(Request $request, $kode_brand)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandfasilitas,kode_brand,' . $kode_brand . ',kode_brand',
        ]);

        $brandFasilitas = BrandFasilitas::findOrFail($kode_brand);
        $brandFasilitas->update($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Brand berhasil diupdate.');
    }

    public function destroyBrandFasilitas($kode_brand)
    {
        $brandFasilitas = BrandFasilitas::findOrFail($kode_brand);
        $brandFasilitas->delete();

        return redirect()->route('datafasilitas.index')->with('success', 'Brand berhasil dihapus.');
    }

    public function createJenisFasilitas()
    {
        return view('jenisfasilitas.create');
    }

    public function storeJenisFasilitas(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'kode_fasilitas' => 'required|unique:jenisfasilitas,kode_fasilitas',
        ]);

        JenisFasilitas::create($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Jenis berhasil ditambahkan.');
    }

    public function editJenisFasilitas($kode_fasilitas)
    {
        $jenisFasilitas = JenisFasilitas::findOrFail($kode_fasilitas);
        return view('jenisfasilitas.edit', compact('jenisFasilitas'));
    }


    public function updateJenisFasilitas(Request $request, $kode_fasilitas)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'kode_fasilitas' => 'required|unique:jenisfasilitas,kode_fasilitas,' . $kode_fasilitas . ',kode_fasilitas',
        ]);

        $jenisFasilitas = JenisFasilitas::findOrFail($kode_fasilitas);
        $jenisFasilitas->update($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Jenis berhasil diupdate.');
    }

    public function destroyJenisFasilitas($kode_fasilitas)
    {
        $jenisFasilitas = JenisFasilitas::findOrFail($kode_fasilitas);
        $jenisFasilitas->delete();

        return redirect()->route('datafasilitas.index')->with('success', 'Jenis berhasil dihapus.');
    }
    // ------------------------------- ALAT UKUR -------------------------------


    public function indexAlatukur()
    {
        $brandalatukur = BrandAlatukur::all();
        $jenisalatukur = JenisAlatukur::all();
        return view('menu.data.dataalatukur', compact('brandalatukur', 'jenisalatukur'));
    }
    public function createBrandAlatukur()
    {
        return view('brandalatukur.create');
    }

    public function storeBrandAlatukur(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandalatukur,kode_brand',
        ]);

        BrandAlatukur::create($request->all());

        return redirect()->route('dataalatukur.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function editBrandAlatukur($kode_brand)
    {
        $brandAlatukur = BrandAlatukur::findOrFail($kode_brand);
        return view('brandalatukur.edit', compact('brandAlatukur'));
    }


    public function updateBrandAlatukur(Request $request, $kode_brand)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandalatukur,kode_brand,' . $kode_brand . ',kode_brand',
        ]);

        $brandAlatukur = BrandAlatukur::findOrFail($kode_brand);
        $brandAlatukur->update($request->all());

        return redirect()->route('dataalatukur.index')->with('success', 'Brand berhasil diupdate.');
    }

    public function destroyBrandAlatukur($kode_brand)
    {
        $brandAlatukur = BrandAlatukur::findOrFail($kode_brand);
        $brandAlatukur->delete();

        return redirect()->route('dataalatukur.index')->with('success', 'Brand berhasil dihapus.');
    }

    public function createJenisAlatukur()
    {
        return view('jenisalatukur.create');
    }

    public function storeJenisAlatukur(Request $request)
    {
        $request->validate([
            'nama_alatukur' => 'required',
            'kode_alatukur' => 'required|unique:jenisalatukur,kode_alatukur',
        ]);

        JenisAlatukur::create($request->all());

        return redirect()->route('dataalatukur.index')->with('success', 'Jenis berhasil ditambahkan.');
    }

    public function editJenisAlatukur($kode_alatukur)
    {
        $jenisAlatukur = JenisAlatukur::findOrFail($kode_alatukur);
        return view('jenisalatukur.edit', compact('jenisAlatukur'));
    }


    public function updateJenisAlatukur(Request $request, $kode_alatukur)
    {
        $request->validate([
            'nama_alatukur' => 'required',
            'kode_alatukur' => 'required|unique:jenisalatukur,kode_alatukur,' . $kode_alatukur . ',kode_alatukur',
        ]);

        $jenisAlatukur = JenisAlatukur::findOrFail($kode_alatukur);
        $jenisAlatukur->update($request->all());

        return redirect()->route('dataalatukur.index')->with('success', 'Jenis berhasil diupdate.');
    }

    public function destroyJenisAlatukur($kode_alatukur)
    {
        $jenisAlatukur = JenisAlatukur::findOrFail($kode_alatukur);
        $jenisAlatukur->delete();

        return redirect()->route('dataalatukur.index')->with('success', 'Jenis berhasil dihapus.');
    }

    // ------------------------------- REGION -------------------------------


    public function indexRegion()
    {
        $regions = Region::all();
        $sites = Site::all();
        return view('menu.data.dataregion', compact('regions', 'sites'));
    }
    public function createRegion()
    {
        return view('region.create');
    }

    public function storeRegion(Request $request)
    {
        $request->validate([
            'nama_region' => 'required',
            'kode_region' => 'required|unique:region,kode_region',
            'email' => 'required|email',
            'alamat' => 'nullable',
            'koordinat' => 'nullable',
        ]);

        Region::create($request->all());

        return redirect()->route('dataregion.index')->with('success', 'Region berhasil ditambahkan.');
    }

    public function editRegion($id_region)
    {
        $region = Region::findOrFail($id_region);
        return view('region.edit', compact('region'));
    }


    public function updateRegion(Request $request, $id_region)
    {
        $request->validate([
            'nama_region' => 'required',
            'kode_region' => 'required|unique:region,kode_region,' . $id_region . ',id_region',
            'email' => 'required|email',
            'alamat' => 'nullable',
            'koordinat' => 'nullable',
        ]);

        $region = Region::findOrFail($id_region);
        $region->update($request->all());

        return redirect()->route('dataregion.index')->with('success', 'Region berhasil diupdate.');
    }

    public function destroyRegion($id_region)
    {
        $region = Region::findOrFail($id_region);
        $region->delete();

        return redirect()->route('dataregion.index')->with('success', 'Region berhasil dihapus.');
    }

    public function createSite()
    {
        return view('site.create');
    }

    public function storeSite(Request $request)
    {
        $request->validate([
            'nama_site' => 'required',
            'kode_site' => 'required|unique:site,kode_site',
            'jenis_site' => 'required',
            'kode_region' => 'required',
            'jml_rack' => 'nullable',
        ]);

        Site::create($request->all());

        return redirect()->route('dataregion.index')->with('success', 'Site berhasil ditambahkan.');
    }

    public function editSite($id_site)
    {
        $site = Site::findOrFail($id_site);
        return view('site.edit', compact('site'));
    }


    public function updateSite(Request $request, $id_site)
    {
        $request->validate([
            'nama_site' => 'required',
            'kode_site' => 'required|unique:site,kode_site,' . $id_site . ',id_site',
            'jenis_site' => 'required',
            'kode_region' => 'required',
            'jml_rack' => 'nullable',
        ]);

        $site = Site::findOrFail($id_site);
        $site->update($request->all());

        return redirect()->route('dataregion.index')->with('success', 'Site berhasil diupdate.');
    }

    public function destroySite($id_site)
    {
        $site = Site::findOrFail($id_site);
        $site->delete();

        return redirect()->route('dataregion.index')->with('success', 'Site berhasil dihapus.');
    }

    public function indexUser()
    {
        $users = User::all();
        $regions = Region::all();
        return view('menu.data.datauser', compact('users', 'regions'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => $request->isMethod('post') ? 'required|string|min:6' : 'nullable|string|min:6',
            'role' => 'required|in:1,2,3,4,5',
            'region' => 'required|string',
            'mobile_number' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'perusahaan' => 'required|string|max:255',
            'noktp' => ['required', 'digits:16', 'regex:/^[0-9]+$/'],
            'alamat' => 'required|string|max:255',
            'bagian' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama wajib diisi!',
            'name.max' => 'Nama maksimal 255 karakter!',

            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',

            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',

            'role.required' => 'Role wajib dipilih!',
            'role.in' => 'Role yang dipilih tidak valid!',

            'region.required' => 'Region wajib diisi!',

            'mobile_number.required' => 'No Telepon wajib diisi!',
            'mobile_number.max' => 'No Telepon maksimal 15 karakter!',
            'mobile_number.regex' => 'Format No Telepon hanya boleh angka, spasi, +, dan -',

            'perusahaan.required' => 'Perusahaan wajib diisi!',
            'perusahaan.max' => 'Perusahaan maksimal 255 karakter!',

            'noktp.required' => 'No KTP wajib diisi!',
            'noktp.digits' => 'No KTP harus terdiri dari 16 digit angka!',
            'noktp.regex' => 'No KTP hanya boleh berisi angka!',

            'alamat.required' => 'Alamat wajib diisi!',
            'alamat.max' => 'Alamat maksimal 255 karakter!',

            'bagian.required' => 'Bagian wajib diisi!',
            'bagian.max' => 'Bagian maksimal 255 karakter!',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'region' => $request->region,
            'mobile_number' => $request->mobile_number,
            'noktp' => $request->noktp,
            'perusahaan' => $request->perusahaan,
            'bagian' => $request->bagian,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('datauser.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id_user)
    {
        $user = User::findOrFail($id_user);
        $regions = Region::all();
        return view('user.edit', compact('user', 'regions'));
    }

    public function updateUser(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:1,2,3,4,5',
            'region' => 'required|string',
            'mobile_number' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'perusahaan' => 'required|string|max:255',
            'noktp' => ['required', 'digits:16', 'regex:/^[0-9]+$/'],
            'alamat' => 'required|string|max:255',
            'bagian' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama wajib diisi!',
            'name.max' => 'Nama maksimal 255 karakter!',

            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',

            'password.min' => 'Password minimal 6 karakter!',

            'role.required' => 'Role wajib dipilih!',
            'role.in' => 'Role yang dipilih tidak valid!',

            'region.required' => 'Region wajib diisi!',

            'mobile_number.required' => 'No Telepon wajib diisi!',
            'mobile_number.max' => 'No Telepon maksimal 15 karakter!',
            'mobile_number.regex' => 'Format No Telepon hanya boleh angka, spasi, +, dan -',

            'perusahaan.required' => 'Perusahaan wajib diisi!',
            'perusahaan.max' => 'Perusahaan maksimal 255 karakter!',

            'noktp.required' => 'No KTP wajib diisi!',
            'noktp.digits' => 'No KTP harus terdiri dari 16 digit angka!',
            'noktp.regex' => 'No KTP hanya boleh berisi angka!',

            'alamat.required' => 'Alamat wajib diisi!',
            'alamat.max' => 'Alamat maksimal 255 karakter!',

            'bagian.required' => 'Bagian wajib diisi!',
            'bagian.max' => 'Bagian maksimal 255 karakter!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Gagal update user, silakan coba lagi.');
        }

        $data = $request->only('name', 'email', 'region', 'role', 'alamat', 'perusahaan', 'bagian', 'noktp', 'mobile_number');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('datauser.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroyUser($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();
        return redirect()->route('datauser.index')->with('success', 'User berhasil dihapus.');
    }

    public function indexTipeJaringan()
    {
        $tipeJaringan = TipeJaringan::orderBy('kode_tipejaringan', 'asc')->get();
        return view('menu.data.datajaringan', compact('tipeJaringan'));
    }
}

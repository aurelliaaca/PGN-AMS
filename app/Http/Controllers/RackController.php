<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Region;
use App\Models\Site;
use App\Models\ListFasilitas;
use App\Models\ListPerangkat;

class RackController extends Controller
{
    public function indexRack() {
        $regions = Region::all();
        $sites = Site::all();
        $racks = Rack::with(['region', 'site'])
            ->select('kode_region', 'kode_site', 'no_rack')
            ->groupBy('kode_region', 'kode_site', 'no_rack')
            ->get();
        
        return view('menu.rack', compact('regions', 'sites', 'racks'));
    }
    
    public function loadRacks(Request $request) {
        $query = Rack::with(['region', 'site', 'listperangkat', 'listfasilitas'])
            ->select('kode_region', 'kode_site', 'no_rack')
            ->groupBy('kode_region', 'kode_site', 'no_rack');
            
        // Apply filters if provided
        if ($request->has('region') && $request->region !== 'all') {
            $query->where('kode_region', $request->region);
        }
        
        if ($request->has('site') && $request->site !== 'all') {
            $query->where('kode_site', $request->site);
        }
        
        $racks = $query->get()
            ->map(function ($rack) {
                $rackDetails = Rack::with(['listperangkat', 'listfasilitas'])
                    ->where('kode_region', $rack->kode_region)
                    ->where('kode_site', $rack->kode_site)
                    ->where('no_rack', $rack->no_rack)
                    ->orderBy('u', 'desc')
                    ->get();
                
                // Calculate total U based on the number of unique rows
                $totalU = $rackDetails->count();
                
                // Calculate filled and empty U's
                $filledU = $rackDetails->filter(function ($detail) {
                    return !is_null($detail->id_perangkat) || !is_null($detail->id_fasilitas);
                })->count();
                $emptyU = $totalU - $filledU;
                
                // Count unique devices (based on id_perangkat)
                $uniqueDevices = $rackDetails->pluck('listperangkat.id_perangkat')->unique()->filter()->count();
                
                // Count unique facilities (based on id_fasilitas)
                $uniqueFacilities = $rackDetails->pluck('listfasilitas.id_fasilitas')->unique()->filter()->count();
                
                $rack->details = $rackDetails;
                $rack->filled_u = $filledU;
                $rack->empty_u = $emptyU;
                $rack->device_count = $uniqueDevices;
                $rack->facility_count = $uniqueFacilities;
                
                return $rack;
            });
        
        $regions = Region::all();
        
        return response()->json([
            'racks' => $racks,
            'regions' => $regions,
            'totalRacks' => $racks->count()
        ]);
    }

    public function getSites(Request $request)
{
    $regions = $request->get('regions', []);
    $sites = Site::whereIn('kode_region', $regions)
                 ->pluck('nama_site', 'kode_site');
    return response()->json($sites);
}

    public function storeRack(Request $request)
    {
        $validated = $request->validate([
            'kode_region' => 'required|string',
            'kode_site' => 'required|string',
            'no_rack' => 'required|string',
            'total_u' => 'required|integer|min:1',
        ]);

        for ($i = 1; $i <= $validated['total_u']; $i++) {
            Rack::create([
                'kode_region' => $validated['kode_region'],
                'kode_site' => $validated['kode_site'],
                'no_rack' => $validated['no_rack'],
                'u' => $i,
                'id_fasilitas' => null,
                'id_perangkat' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Rack berhasil ditambahkan!');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'kode_region' => 'required|string',
            'kode_site' => 'required|string',
            'no_rack' => 'required|string',
        ]);

        // Check if any U in the rack has devices or facilities
        $hasOccupiedU = Rack::where('kode_region', $validated['kode_region'])
            ->where('kode_site', $validated['kode_site'])
            ->where('no_rack', $validated['no_rack'])
            ->where(function($query) {
                $query->whereNotNull('id_perangkat')
                    ->orWhereNotNull('id_fasilitas');
            })
            ->exists();

        if ($hasOccupiedU) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus rack karena masih ada perangkat atau fasilitas yang terpasang.'
            ]);
        }

        // Delete all U's in the rack
        $deleted = Rack::where('kode_region', $validated['kode_region'])
            ->where('kode_site', $validated['kode_site'])
            ->where('no_rack', $validated['no_rack'])
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Rack berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus rack'
        ]);
    }

    public function destroyData(Request $request)
    {
        $region = $request->kode_region;
        $site = $request->kode_site;
        $rack = $request->no_rack;
        $u = $request->u;

        // Ambil 1 baris data Rack berdasarkan region, site, no rack, dan U
        $dataRack = Rack::where('kode_region', $region)
            ->where('kode_site', $site)
            ->where('no_rack', $rack)
            ->where('u', $u)
            ->firstOrFail();

        // Kosongkan id_perangkat jika ada
        if ($dataRack->id_perangkat) {
            // Kosongkan id_perangkat di semua baris yang pakai id ini
            Rack::where('id_perangkat', $dataRack->id_perangkat)
                ->update(['id_perangkat' => null]);
        }

        // Kosongkan id_fasilitas jika ada
        if ($dataRack->id_fasilitas) {
            // Kosongkan id_fasilitas di semua baris yang pakai id ini
            Rack::where('id_fasilitas', $dataRack->id_fasilitas)
                ->update(['id_fasilitas' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perangkat/Fasilitas berhasil dihapus dari rack'
        ]);
    }
}
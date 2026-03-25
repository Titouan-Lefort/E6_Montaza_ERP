<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\DossierStandard;
use App\Models\Standard;
use App\Models\StandardVersion;


class StandardSeeder extends Seeder
{
    public function run(): void
    {
        $p = Storage::path('standards');
        $ds = File::directories($p);
        DB::beginTransaction();
        foreach ($ds as $d) {
            $fs = File::files($d);
            $doss = new DossierStandard;
            $doss->nom = basename($d);
            $doss->save();
            foreach ($fs as $f) {
                if ($f->getExtension() === 'pdf') {
                    $std = new Standard();
                    $std->nom = str_replace('.pdf', '', $f->getFilename());
                    $std->dossier_standard_id = $doss->id;
                    $std->save();

                    $ver = new StandardVersion();
                    $ver->standard_id = $std->id;
                    $ver->version = 'A';
                    $ver->chemin_pdf = 'standards/' . $doss->nom . '/' . $std->nom . '.pdf';
                    $ver->save();
                }
            }
        }
        DB::commit();
    }
}

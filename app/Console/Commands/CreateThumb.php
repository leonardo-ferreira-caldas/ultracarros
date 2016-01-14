<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use Image;

class CreateThumb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:thumb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('carro')
            ->select('id_carro')
            ->whereNull('foto_capa')
            ->chunk(100, function($users)
        {
            foreach ($users as $user)
            {
                $img = DB::table('carro_foto')
                    ->where('fk_carro', '=', $user->id_carro)
                    ->orderBy('id_carro_foto', 'asc')->first();

                $s3 = "https://s3-sa-east-1.amazonaws.com/fotoscarros/" . $img->nome_foto;

                $thumb = Image::make($s3);
                $thumb->fit(255, 135);
                $tmp = '/tmp/' . $img->nome_foto;
                $thumb->save($tmp);
                $thumb->destroy();

                $split = explode(".", $img->nome_foto);
                $extension = array_pop($split);
                $s3Name = implode(".", $split);
                $s3Name .= "-255x135." . $extension;

                Storage::disk('s3')->put($s3Name, fopen($tmp, 'r+'), 'public');
                unlink($tmp);

                DB::table('carro')->where('id_carro','=', $user->id_carro)->update(['foto_capa' => $s3Name]);

            }

        });
    }
}

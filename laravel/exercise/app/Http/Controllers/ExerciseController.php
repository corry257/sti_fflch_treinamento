<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use League\Csv\Reader;

class ExerciseController extends Controller
{
    public function importCsv()
    {
        // Configura o leitor de CSV
        $csv = Reader::createFromPath(storage_path('app/exercise.csv'), 'r');
        $csv->setHeaderOffset(0); // Ignora o cabeçalho
        
        // Limpa a tabela antes de importar
        Exercise::truncate();
        
        // Importa cada linha
        foreach ($csv as $linha) {
            $exercicio = new Exercise();
            $exercicio->diet = $linha['diet'];
            $exercicio->pulse = $linha['pulse'];
            $exercicio->time = $linha['time'];
            $exercicio->kind = $linha['kind'];
            $exercicio->save();
        }
        
        return redirect('/exercises/stats');
    }

    public function stats()
    {
        // Calculo das estatísticas 
        $estatistica = [
            'rest' => [
                'quantidade' => Exercise::where('kind', 'rest')->count(),
                'media_pulse' => Exercise::where('kind', 'rest')->avg('pulse')
            ],
            'walking' => [
                'quantidade' => Exercise::where('kind', 'walking')->count(),
                'media_pulse' => Exercise::where('kind', 'walking')->avg('pulse')
            ],
            'running' => [
                'quantidade' => Exercise::where('kind', 'running')->count(),
                'media_pulse' => Exercise::where('kind', 'running')->avg('pulse')
            ]
        ];
        
        return view('exercises.stats', ['dados' => $estatistica]);
    }
}
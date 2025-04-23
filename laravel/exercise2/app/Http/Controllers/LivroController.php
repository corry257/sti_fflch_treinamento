<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use League\Csv\Reader;

class LivroController extends Controller
{
    // Lista todos os livros
    public function index()
    {
        $livros = Livro::all();
        return view('livros.index', ['livros' => $livros]);
    }

    // Mostra formulário de criação
    public function create()
    {
        return view('livros.create');
    }

    // Salva novo livro (POST)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'authors' => 'required',
            'isbn' => 'required|unique:livros',
        ]);

        $livro = new Livro();
        $livro->title = $request->title;
        $livro->authors = $request->authors;
        $livro->isbn = $request->isbn;
        $livro->editora = $request->editora; 
        $livro->original_publication_year = $request->original_publication_year; // E esta
        $livro->language_code = $request->language_code; 
        $livro->save();

        return redirect('/livros');
    }

    // Mostra um livro específico
    public function show(Livro $livro)
    {
        return view('livros.show', ['livro' => $livro]);
    }

    // Mostra formulário de edição
    public function edit(Livro $livro)
    {
        return view('livros.edit', ['livro' => $livro]);
    }

    // Atualiza livro (PUT)
    public function update(Request $request, Livro $livro)
    {
        $livro->title = $request->title;
        $livro->authors = $request->authors;
        $livro->isbn = $request->isbn;
        $livro->editora = $request->editora;
        $livro->original_publication_year = $request->original_publication_year;
        $livro->language_code = $request->language_code;
        $livro->image_url = $request->image_url;
        $livro->save();
        return redirect('/livros/' . $livro->id);
    }

    // Deleta livro
    public function destroy(Livro $livro)
    {
        $livro->delete();
        return redirect('/livros');
    }

    // Importa CSV
    public function importCsv()
    {
        $csv = Reader::createFromPath(storage_path('app/books.csv'), 'r'); // lê arquivo csv
        $csv->setHeaderOffset(0); // Ignora primeinha linha 

        $importados = 0;
        $duplicados = 0;

        foreach ($csv as $linha) {
            // Verifica se o ISBN já existe
            if (!Livro::where('isbn', $linha['isbn'])->exists()) {
                $livro = new Livro();
                $livro->title = $linha['title'] ?? '';
                $livro->authors = $linha['authors'] ?? '';
                $livro->isbn = $linha['isbn'] ?? '';
                $livro->editora = $linha['publisher'] ?? $linha['editora'] ?? null;
                $livro->original_publication_year = $linha['original_publication_year'] ?? null;
                $livro->language_code = $linha['language_code'] ?? null;
                $livro->save();
                $importados++;
            } else {
                $duplicados++;
            }
        }

        return redirect('/livros')->with([
            'success' => "Importação concluída! $importados novos registros, $duplicados duplicados ignorados."
        ]);
    }

    // Página central de estatísticas
    public function stats()
    {
        return view('livros.stats', [
            'totalLivros' => Livro::count() // Envia o total para a view
        ]);
    }

    // Estatísticas por ano
    public function statsAno()
    {
        $porAno = Livro::selectRaw('original_publication_year as ano, count(*) as total')
            ->groupBy('original_publication_year')
            ->orderBy('original_publication_year')
            ->get();

            return view('livros.stats_ano', [
                'porAno' => $porAno,
                'totalLivros' => Livro::count() 
            ]);
        }

    // Estatísticas por autor
    public function statsAutor()
    {
        $porAutor = Livro::selectRaw('authors as autor, count(*) as total')
            ->groupBy('authors')
            ->orderBy('total', 'desc')
            ->get();

        return view('livros.stats_autor', [
            'porAutor' => $porAutor,
            'totalLivros' => Livro::count()
        ]);
    }

    // Estatísticas por idioma
    public function statsIdioma()
    {
        $porIdioma = Livro::selectRaw('language_code as idioma, count(*) as total')
            ->groupBy('language_code')
            ->orderBy('total', 'desc')
            ->get();

        return view('livros.stats_idioma', [
            'porIdioma' => $porIdioma,
            'totalLivros' => Livro::count()
        ]);
    }
}
# Dia 1

## Instalação

Antes de instalar o Laravel no Debian, é necessário garantir que todas as dependências estejam instaladas.  
O Laravel depende do PHP e de algumas extensões, além de um banco de dados como MariaDB ou Sqlite. Aqui estão os principais pacotes que devem ser instalados no Debian:

```bash
sudo apt-get install php php-common php-cli php-gd php-curl php-xml php-mbstring php-zip php-sybase php-mysql php-sqlite3
sudo apt-get install mariadb-server sqlite3 git
```
O Composer é um gerenciador de dependências para PHP. Ele permite instalar, atualizar e gerenciar bibliotecas e pacotes de forma simples, garantindo que um projeto tenha todas as dependências necessárias.   
No Laravel, o Composer é usado para instalar o framework e suas bibliotecas.

```bash
curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Além disso, é importante configurar o banco de dados, pois ele será usado para instalar o Laravel. Vamos inicialmente criar um usuário admin com senha admin e criar um banco de dados chamado treinamento:

```bash
sudo mariadb
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%'  IDENTIFIED BY 'admin' WITH GRANT OPTION;
CREATE database treinamento;
quit
```

Crie uma pasta onde você irá fazer esse treinamento, pode chama-la de treinamento ou projetos:

```bash
mkdir treinamento
cd treinamento
```

O comando a seguir cria um novo projeto Laravel na pasta dia_01, baixando a estrutura básica do framework e instalando todas as dependências necessárias via Composer, garantindo que o ambiente esteja pronto para o desenvolvimento:

```bash
composer create-project laravel/laravel dia_01
cd dia_01
php artisan serve
```

## MVC

Uma rota é a forma como o framework define e gerencia URLs para acessar diferentes partes da aplicação. As rotas são configuradas no arquivo routes/web.php (para páginas web) ou routes/api.php (para APIs) e determinam qual código será executado quando um usuário acessa uma URL específica. Exemplo:

```php
Route::get('/exemplo-de-rota', function () {
echo "Uma rota sem controller, not good!";
});
```

O controller é uma classe responsável por organizar a lógica da aplicação, separando as regras de negócio das rotas.  
Em vez de definir toda a lógica diretamente nas rotas, os controllers agrupam funcionalidades relacionadas, tornando o código mais limpo e modular. A convenção de nomenclatura para controllers segue o padrão PascalCase, onde o nome deve ser descritivo, no singular e sempre terminar com “Controller”, como ProdutoController ou UsuarioController. Vamos criar o EstagiarioController com o seguinte comando que gera automaticamente o arquivo correspondente dentro de app/Http/Controllers:

```bash
php artisan make:controller EstagiarioController
```

A seguir criamos a rota estagiarios e a apontamos para o controller EstagiarioController, importando anteriormente o namespace App\Http\Controllers\EstagiarioController.  
O namespace é uma forma de organizar classes, funções e constantes para evitar conflitos de nomes em projetos grandes. Ele permite agrupar elementos relacionados dentro de um mesmo escopo, facilitando a reutilização e manutenção do código.

```php
use App\Http\Controllers\EstagiarioController;

Route::get('/estagiarios', [EstagiarioController::class,'index']);
```

A camada View é responsável por exibir a interface da aplicação, separando a lógica de apresentação da lógica de negócio (controller). Ela utiliza o Blade, uma linguagem de templates que permite criar páginas dinâmicas de forma eficiente.  
As views ficam armazenadas na pasta resources/views e podem ser retornadas a partir de um controller usando return view('nome_da_view').

```bash
mkdir resources/views/estagiarios
touch resources/views/estagiarios/index.blade.php
```

No controller:

```php
public function index()
    {
        return view('estagiarios.index');
    }
```

Conteúdo mínimo de index.blade.php:

```html
<!DOCTYPE html>
<html>
    <head>
        <title>Estagiários</title>
</head>
    <body>
        João<br>
        Maria
    </body>
</html>
```

O Model é uma representação de uma tabela no banco de dados e é responsável pela interação com os dados dessa tabela. Ele encapsula a lógica de acesso e manipulação dos dados, permitindo realizar operações como inserção, atualização, exclusão e leitura de registros de forma simples e intuitiva.  
O Laravel usa o Eloquent ORM (Object-Relational Mapping) para mapear os dados do banco de dados para objetos PHP, o que permite que você trabalhe com as tabelas como se fosse uma classe de objetos.  
  
Criando o model chamado Estagiario:

```bash
php artisan make:model Estagiario -m
```

As migrations são uma forma de versionar e gerenciar o esquema do banco de dados, permitindo criar, alterar e remover tabelas de forma controlada e rastreável. Elas funcionam como um histórico de mudanças no banco de dados, ajudando a manter o controle de versões entre diferentes ambientes de desenvolvimento e produção.  

Cada migration é uma classe PHP que define as operações a serem realizadas no banco de dados. As migrations são armazenadas na pasta database/migrations. As migrations tornam o processo de gerenciamento do banco de dados mais organizado e flexível, principalmente em projetos com múltiplos desenvolvedores.  
  
Vamos colocar três colunas para o model Estagiario: nome, idade e email.

```php
$table->string('nome');
$table->string('email');
$table->integer('idade');
```

Faça a migração para o banco de dados.

```bash
php artisan migrate
```

## Desafio

Crie uma rota chamada estagiarios/create apontando para o método create em EstagiarioController, que também deve ser criado.  
  
No método create do EstagiarioController, insira os estagiários:

```php
public function create(){
    $estagiario1 = new \App\Models\Estagiario;
    $estagiario1->nome = "João";
    $estagiario1->email = "joao@usp.br";
    $estagiario1->idade = 26;
    $estagiario1->save();

    $estagiario2 = new \App\Models\Estagiario;
    $estagiario2->nome = "Maria";
    $estagiario2->email = "maria@usp.br";
    $estagiario2->idade = 27;
    $estagiario2->save();
    return redirect("/estagiarios");
}
```

**Dica**  
  
*Toda vez que a rota estagiarios/create for acessada os cadastros serão realizados, pode-se deletar tudo antes das inserções com a função: App\Models\Estagiario::truncate()*  
  
Por fim, na view da index podemos buscar os estagiários cadastrados e passar como uma variável para o template:

```php
public function index(){
    return view('estagiarios.index', [
        'estagiarios' => App\Models\Estagiario::all()
    ]);
}
```

No blade, listamos os estagiários:

```html
<ul>
    @foreach($estagiarios as $estagiario)
        <li>{{ $estagiario->nome }} - {{ $estagiario->email }} - {{ $estagiario->idade }} anos</li>
    @endforeach
</ul>
```

## Exercício 1 - Importação de Dados e Estatísticas com Laravel

Objetivo: Criar um sistema básico em Laravel para importar dados de um arquivo CSV e exibir estatísticas desses dados em uma view.  

[https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv](https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv)  

1) Criar o Model e a Migration:  

    Crie um model chamado Exercise com uma migration correspondente.  
    Na migration, defina os campos necessários com base nas colunas do arquivo exercise.csv  
    Execute a migration para criar a tabela no banco de dados.  
  
2) Criar o Controller e a Rota para Importação  

    Crie um controller chamado ExerciseController com o método importCsv.  
    Defina uma rota exercises/importcsv que aponte para o método importCsv do controller.  
    No método importCsv, implemente a lógica para ler o arquivo exercise.csv e salvar os dados no banco de dados usando o model Exercise.  
  
*Dica: Você pode usar a classe League\Csv\Reader (disponível via Composer) para facilitar a leitura do CSV.*  
  
3) Criar a Rota e Método para Estatísticas  
  
    No mesmo ExerciseController, crie um método chamado stats.  
    Defina uma rota exercises/stats que aponte para o método stats.  
    No método stats, calcule as média da coluna pulse para os casos rests, walking e running, conforme tabela abaixo.  
    Passe esses dados para uma view chamada resources/views/exercises/stats.blade.php e monte finalmente a tabela com html.  
  
Exemplo de saída:  
  
|exercise.csv	|rest	|walking|running|
|---------------|-------|-------|-------|
|Qtde linhas	| XX	|   XX  |  XXX  |
|Média Pulse	| XX	| XX	|  XXX  |
  
**Resolução passo a passo**  

1. Preparação Inicial

    Instale a biblioteca League\CSV:

       composer require league/csv

2. Baixar e Preparar o Arquivo CSV

    Acesse o link no navegador:

    [https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv](https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv)

    Clique com o botão direito e selecione "Salvar como"

    Salve o arquivo como exercise.csv na pasta storage/app do seu projeto

3. Criar Model e Migration

    Crie o model com migration:

        php artisan make:model Exercise -m


   Edite a migration em (database/migrations/xxxx_create_exercises_table.php):

       $table->string('diet');
       $table->integer('pulse');
       $table->string('time');
       $table->string('kind');

    Execute a migration:

       php artisan migrate

5. Criar o Controller

    Crie o controller com o nome de ExerciseController:

       php artisan make:controller ExerciseController

    Edite o controller em (app/Http/Controllers/ExerciseController.php):

       use App\Models\Exercise;
       use League\Csv\Reader;

    Depois crie o método importCsv:

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

     Agora crie o método stats: 
     
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
   

7. Configurar as Rotas

    Edite as rotas em (routes/web.php):
    
        use App\Http\Controllers\ExerciseController;
    
        Route::get('/exercises/importcsv', [ExerciseController::class, 'importCsv']);
        Route::get('/exercises/stats', [ExerciseController::class, 'stats']);

6. Criar a View de Estatísticas

    Crie a pasta para as views:

       mkdir -p resources/views/exercises

    Crie o arquivo resources/views/exercises/stats.blade.php:

       touch resources/views/exercises/stats.blade.php

   Edite a view stats.blade.php:

        <!DOCTYPE html>
        <html>
        <head>
            <title>Estatísticas</title>
            <style>
                table { border-collapse: collapse; width: 80%; margin: 20px auto; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1 style="text-align: center;">Estatísticas</h1>
            
            <table>
                <tr>
                    <th>exercise.csv</th>
                    <th>rest</th>
                    <th>walking</th>
                    <th>running</th>
                </tr>
                <tr>
                    <td>Qtde linhas</td>
                    <td>{{ $dados['rest']['quantidade'] }}</td>
                    <td>{{ $dados['walking']['quantidade'] }}</td>
                    <td>{{ $dados['running']['quantidade'] }}</td>
                </tr>
                <tr>
                    <td>Média Pulse</td>
                    <td>{{ round($dados['rest']['media_pulse'], 1) }}</td>
                    <td>{{ round($dados['walking']['media_pulse'], 1) }}</td>
                    <td>{{ round($dados['running']['media_pulse'], 1) }}</td>
                </tr>
            </table>
        </body>
        </html>

8. Testar a Aplicação

    Inicie o servidor de desenvolvimento:

       php artisan serve

    Acesse no navegador:

    Para importar os dados:

        http://localhost:8000/exercises/import

    Para ver as estatísticas:

        http://localhost:8000/exercises/stats




# Dia 2

### CRUD

CRUD é um acrônimo para as quatro operações básicas utilizadas na manipulação de dados em sistemas web: Create (Criar), Read (Ler), Update (Atualizar) e Delete (Excluir). Essas operações interagem com bancos de dados, permitindo, por exemplo, que usuários possam cadastrar novas informações, visualizar registros existentes, modificar dados já salvos e remover registros.

Vamos criar um novo model Chamado Livro e a correspondente migration:

```bash
php artisan make:model Livro -m
```

Edite a migration (database/migrations/xxxx_create_livros_table.php):

```php
$table->string('titulo');
$table->string('autor')->nullable();
$table->string('isbn');
```

Execute a migration:

```bash
php artisan migrate
```

### Create

São geralmente necessárias duas rotas para salvar um registro em uma operação CRUD porque o processo é dividido em duas etapas: exibir o formulário e processar os dados enviados. A rota GET serve para exibir o formulário de criação e a rota POST serve para processar os dados enviados pelo formulário no controller:

```php
use App\Http\Controllers\LivroController;

// CREATE
Route::get('/livros/create', [LivroController::class, 'create']);
Route::post('/livros', [LivroController::class, 'store']);
```

Para mostrar o formulário html usamos o método create e store.

Crie um controller chamado LivroController:

```bash
php artisan make:controller LivroController
```
acrescente os métodos create e store no controle:

```php
use App\Models\Livro;


class LivroController extends Controller
{
    // CREATE
    public function create()
    {
        return view('livros.create');
    }

    public function store(Request $request)
    {
        $livro = new Livro();
        $livro->titulo = $request->titulo;
        $livro->autor = $request->autor;
        $livro->isbn = $request->isbn;
        $livro->save();

        return redirect('/livros');
    }
}
```
Crie a view create:

```bash
mkdir resources/views/livros
touch resources/views/livros/create.blade.php
```
Formulário html na view create:

```html
<form method="POST" action="/livros">
    @csrf
    Título: <input type="text" name="titulo">
    Autor: <input type="text" name="autor">
    ISBN: <input type="text" name="isbn">
    <button type="submit">Enviar</button>
</form>
```

### Read

Vamos implementar duas formas de acesso aos registros de livros. O acesso ao registro de um livro específico e uma listagem de todos livros:

```php
// READ
Route::get('/livros', [LivroController::class, 'index']);
Route::get('/livros/{livro}', [LivroController::class, 'show']);
```

Respectivos controllers:

```php
// READ
public function index()
{
    $livros =  Livro::all();
    return view('livros.index',[
        'livros' => $livros
    ]);
}

public function show(Livro $livro)
{
    return view('livros.show',[
        'livro' => $livro
    ]);
}
```

Crie a view index:

```bash
touch resources/views/livros/index.blade.php
```

Html para view index:

```html
@forelse($livros as $livro)
    <ul>
        <li><a href="/livros/{{$livro->id}}">{{ $livro->titulo }}</a></li>
        <li>{{ $livro->autor }}</li>
        <li>{{ $livro->isbn }}</li>
    </ul>
@empty
    Não há livros cadastrados
@endforelse
```

Crie a view show:

```bash
touch resources/views/livros/show.blade.php
```

Html para view show:

```html
<h1>{{ $livro->titulo }}</h1>
<p>Autor: {{ $livro->autor }}</p>
<p>ISBN: {{ $livro->isbn }}</p>
<a href="/livros/{{ $livro->id }}/edit">Editar</a>
<form action="/livros/{{ $livro->id }}" method="post">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Tem certeza?')">Apagar</button>
</form>
<a href="/livros">Voltar para lista</a>
```

### Update

Novamente precisamos de duas rotas para atualizar um registro, uma para exibir o formulário e outra para processar os dados enviados:

```php
// UPDATE
Route::get('/livros/{livro}/edit', [LivroController::class, 'edit']);
Route::put('/livros/{livro}', [LivroController::class, 'update']);
```

Implementação no controller:

```php
// UPDATE
public function edit(Livro $livro)
{
    return view('livros.edit', ['livro' => $livro]);
}

public function update(Request $request, Livro $livro)
{
    $livro->titulo = $request->titulo;
    $livro->autor = $request->autor;
    $livro->isbn = $request->isbn;
    $livro->save();
    return redirect("/livros/{$livro->id}");
}
```

**Dica**  
  
*Toda vez que a rota livros/edit for acessada os cadastros serão realizados, pode-se (explicar melhor como a inserções de 
        
        $request->validate([
            'titulo' => 'required',
            'isbn' => 'required|unique:livros,isbn,' . $livro->id,
        ]); 
no método update não vai mostrar na view index livros repetidos após a edição)*  

Crie a view edit:

```bash
touch resources/views/livros/edit.blade.php
```

Html para edição:

```html
<form method="POST" action="/livros">
    @csrf
    Título: <input type="text" name="titulo" value="{{ $livro->titulo }}">
    Autor: <input type="text" name="autor" value="{{ $livro->autor }}">
    ISBN: <input type="text" name="isbn" value="{{ $livro->isbn }}">
    <button type="submit">Enviar</button>
</form>
```

### Delete

Rota para delete:

```php
// DELETE
Route::delete('/livros/{livro}', [LivroController::class,'destroy']);
```

Controller para delete:

```php
// DELETE
public function destroy(Livro $livro)
{
    $livro->delete();
    return redirect('/livros');
}
```

Botão html para delete dentro da view index:

```html
<li>
    <form action="/livros/{{ $livro->id }} " method="post">
    @csrf
    @method('delete')
    <button type="submit" onclick="return confirm('Tem certeza?');">Apagar</button>
    </form>
</li>
```

No final o arquivo index deve estar semlhante ao seguinte:

```html
@forelse($livros as $livro)
    <ul>
        <li><a href="/livros/{{$livro->id}}">{{ $livro->titulo }}</a></li>
        <li>{{ $livro->autor }}</li>
        <li>{{ $livro->isbn }}</li>
        <li>
            <form action="/livros/{{ $livro->id }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Tem certeza?')">Apagar</button>
            </form>
        </li>
    </ul>
@empty
    <p>Não há livros cadastrados</p>
@endforelse
<a href="/livros/create">Adicionar novo livro</a>
```

## Exercício 2 - Importação de Dados e Estatísticas com Laravel

Objetivos:

1) Criar um CRUD completo para cadastro de livros: [https://github.com/zygmuntz/goodbooks-10k/blob/master/samples/books.csv](https://github.com/zygmuntz/goodbooks-10k/blob/master/samples/books.csv)    
 - Criar uma rotina de importação, conforme feito no exercício 1, para importação do csv: [https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv](https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv) (esse é o completo!)  
  
2) Criar rota, controller e view que vai mostrar:  
    - uma tabela com a quantidade de livros por ano  
    - uma tabela com a quantidade de livros por autor  
    - uma tabela com a quantidade de livros por idioma  

**Resolução Passo a passo**

1. Preparação Inicial

    Criar novo projeto Laravel:

```bash
composer create-project laravel/laravel exercise2
cd exercise2
```

  Instalar a biblioteca League/CSV:

```bash
composer require league/csv
```

2. Criar Model e Migration

    Criar model Livro com migration:

```bash
php artisan make:model Livro -m
```

   Editar migration (database/migrations/xxxx_create_livros_table.php):

```php
// Campos obrigatórios
    $table->string('title');
    $table->text('authors');
    $table->string('isbn')->unique();

    // Campos opcionais
    $table->integer('book_id')->nullable();
    $table->text('original_title')->nullable();
    $table->float('original_publication_year')->nullable();
    $table->string('language_code', 10)->nullable();
    $table->string('editora')->nullable();
    $table->float('average_rating')->nullable();
    $table->integer('ratings_count')->default(0)->nullable();
    $table->integer('work_ratings_count')->default(0)->nullable();
    $table->integer('work_text_reviews_count')->default(0)->nullable();
    $table->integer('ratings_1')->default(0)->nullable();
    $table->integer('ratings_2')->default(0)->nullable();
    $table->integer('ratings_3')->default(0)->nullable();
    $table->integer('ratings_4')->default(0)->nullable();
    $table->integer('ratings_5')->default(0)->nullable();
    $table->text('image_url')->nullable();
    $table->text('small_image_url')->nullable();
    $table->unsignedBigInteger('goodreads_book_id')->nullable();
    $table->unsignedBigInteger('best_book_id')->nullable();
    $table->unsignedBigInteger('work_id')->nullable();
    $table->integer('books_count')->nullable();
```

   Executar migration:

```bash
php artisan migrate
```

3. Criar Controller e Rotas

    Criar controller:

```bash
php artisan make:controller LivroController
```

   Configurar rotas (routes/web.php):

```php
use App\Http\Controllers\LivroController;

Route::get('/livros', [LivroController::class, 'index']);

Route::get('/livros/stats', [LivroController::class, 'stats']);
Route::get('/livros/stats/ano', [LivroController::class, 'statsAno']);
Route::get('/livros/stats/autor', [LivroController::class, 'statsAutor']);
Route::get('/livros/stats/idioma', [LivroController::class, 'statsIdioma']);

Route::get('/livros/importcsv', [LivroController::class, 'importCsv']);

Route::get('/livros/create', [LivroController::class, 'create']);
Route::post('/livros', [LivroController::class, 'store']);
Route::get('/livros/{livro}', [LivroController::class, 'show']);
Route::get('/livros/{livro}/edit', [LivroController::class, 'edit']);
Route::put('/livros/{livro}', [LivroController::class, 'update']);
Route::delete('/livros/{livro}', [LivroController::class, 'destroy']);
```

4. Implementar CRUD no Controller e importação do arquivo csv

Editar app/Http/Controllers/LivroController.php:

```php
<<?php

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
```

5. Criar Views

   Estrutura de pastas:

```bash
mkdir -p resources/views/livros
touch resources/views/livros/{index,create,edit,show,stats,stats_ano,stats_autor,stats_idioma}.blade.php
```

   resources/views/livros/index.blade.php:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Livros</title>
</head>
<body>
    <h1>Livros</h1>

    <a href="/livros/create">Adicionar livro</a> |
    <a href="/livros/stats">Estatísticas</a>

    @forelse($livros as $livro)
        <div style="margin: 20px 0;">
            <h3><a href="/livros/{{$livro->id}}">{{ $livro->title }}</a></h3>
            <p>Autor: {{ $livro->authors }}</p>
            <p>ISBN: {{ $livro->isbn }}</p>

            <a href="/livros/{{$livro->id}}/edit">Editar</a> |
            <form action="/livros/{{$livro->id}}" method="post" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Tem certeza?')">Apagar</button>
            </form>
        </div>
    @empty
        <p>Não há livros cadastrados</p>
    @endforelse
</body>
</html>
```

  resources/views/livros/create.blade.php (Formulário de Cadastro)

```html
<!DOCTYPE html>
<html>
<head>
    <title>Novo Livro</title>
</head>
<body>
    <h1>Novo Livro</h1>

    <a href="/livros">← Voltar</a>

    <form method="POST" action="/livros" style="margin-top: 20px;">
        @csrf

        <div style="margin: 10px 0;">
            <label>Título: <input type="text" name="title" required></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Autor: <input type="text" name="authors" required></label>
        </div>

        <div style="margin: 10px 0;">
            <label>ISBN: <input type="text" name="isbn" required></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Editora: <input type="text" name="editora"></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Ano: <input type="number" name="original_publication_year"></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Idioma: <input type="text" name="language_code"></label>
        </div>

        <button type="submit">Salvar</button>
    </form>
</body>
</html>
```
   resources/views/livros/edit.blade.php (Formulário de Edição)

```html
<!DOCTYPE html>
<html>
<head>
    <title>Editar {{ $livro->title }}</title>
</head>
<body>
    <h1>Editar Livro</h1>

    <a href="/livros/{{ $livro->id }}">← Voltar</a>

    <form method="POST" action="/livros/{{ $livro->id }}" style="margin-top: 20px;">
        @csrf
        @method('PUT')

        <div style="margin: 10px 0;">
            <label>Título: <input type="text" name="title" value="{{ $livro->title }}" required></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Autor: <input type="text" name="authors" value="{{ $livro->authors }}" required></label>
        </div>

        <div style="margin: 10px 0;">
            <label>ISBN: <input type="text" name="isbn" value="{{ $livro->isbn }}" required></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Editora: <input type="text" name="editora" value="{{ $livro->editora }}"></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Ano: <input type="number" name="original_publication_year" value="{{ $livro->original_publication_year }}"></label>
        </div>

        <div style="margin: 10px 0;">
            <label>Idioma: <input type="text" name="language_code" value="{{ $livro->language_code }}"></label>
        </div>

        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
```
   resources/views/livros/show.blade.php (Detalhes do Livro)

```html
<!DOCTYPE html>
<html>
<head>
    <title>{{ $livro->title }}</title>
</head>
<body>
    <h1>{{ $livro->title }}</h1>

    <p>Autor: {{ $livro->authors }}</p>
    <p>ISBN: {{ $livro->isbn }}</p>
    <p>Editora: {{ $livro->editora ?? '-' }}</p>
    <p>Ano: {{ $livro->original_publication_year ?? '-' }}</p>
    <p>Idioma: {{ $livro->language_code ?? '-' }}</p>

    <a href="/livros/{{$livro->id}}/edit">Editar</a> |
    <form action="/livros/{{$livro->id}}" method="post" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Tem certeza?')">Apagar</button>
    </form> |
    <a href="/livros">Voltar</a>
</body>
</html>
```
   resources/views/livros/stats.blade.php:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Estatísticas</title>
</head>
<body>
    <h1>Estatísticas</h1>

    <a href="/livros">← Voltar</a>

    <div style="margin: 20px 0;">
        <h2><a href="/livros/stats/ano">Livros por Ano</a></h2>
    </div>

    <div style="margin: 20px 0;">
        <h2><a href="/livros/stats/autor">Livros por Autor</a></h2>
    </div>

    <div style="margin: 20px 0;">
        <h2><a href="/livros/stats/idioma">Livros por Idioma</a></h2>
    </div>
</body>
</html>
```

   resources/views/livros/stats_ano.blade.php:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Livros por Ano</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; }
        th, td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Livros por Ano</h1>

    <a href="/livros/stats">← Voltar</a>

    <table style="margin-top: 20px;">
        <tr>
            <th>Ano</th>
            <th>Quantidade</th>
        </tr>
        @foreach($porAno as $item)
        <tr>
            <td>{{ $item->ano ?? '-' }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
```

   resources/views/livros/stats_autor.blade.php:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Livros por Autor</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; }
        th, td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Livros por Autor</h1>

    <a href="/livros/stats">← Voltar</a>

    <table style="margin-top: 20px;">
        <tr>
            <th>Autor</th>
            <th>Quantidade</th>
        </tr>
        @foreach($porAutor as $item)
        <tr>
            <td>{{ $item->autor ?? '-' }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
```

   resources/views/livros/stats_idioma.blade.php:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Livros por Idioma</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; }
        th, td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Livros por Idioma</h1>

    <a href="/livros/stats">← Voltar</a>

    <table style="margin-top: 20px;">
        <tr>
            <th>Idioma</th>
            <th>Quantidade</th>
        </tr>
        @foreach($porIdioma as $item)
        <tr>
            <td>{{ $item->idioma ?? '-' }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
```

6. Baixar e Preparar o Arquivo CSV

    Acesse o link no navegador:

   [https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv](https://raw.githubusercontent.com/zygmuntz/goodbooks-10k/master/books.csv)

    Salve como books.csv na pasta storage/app do projeto

7. Testar a Aplicação

    Iniciar servidor:

```bash
php artisan serve
```

  Acessar no navegador:

   CRUD: http://localhost:8000/livros

   Importar: http://localhost:8000/livros/importcsv

   Estatísticas: http://localhost:8000/livros/stats

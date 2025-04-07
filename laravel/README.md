# Instalação

Antes de instalar o Laravel no Debian, é necessário garantir que todas as dependências estejam instaladas. O Laravel depende do PHP e de algumas extensões, além de um banco de dados como MariaDB ou Sqlite. Aqui estão os principais pacotes que devem ser instalados no Debian:

    sudo apt-get install php php-common php-cli php-gd php-curl php-xml php-mbstring php-zip php-sybase php-mysql php-sqlite3
    sudo apt-get install mariadb-server sqlite3 git

O Composer é um gerenciador de dependências para PHP. Ele permite instalar, atualizar e gerenciar bibliotecas e pacotes de forma simples, garantindo que um projeto tenha todas as dependências necessárias. No Laravel, o Composer é usado para instalar o framework e suas bibliotecas.

    curl -s https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer

Além disso, é importante configurar o banco de dados, pois ele será usado para instalar o Laravel. Vamos inicialmente criar um usuário admin com senha admin e criar um banco de dados chamado treinamento:

    sudo mariadb
    GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%'  IDENTIFIED BY 'admin' WITH GRANT OPTION;
    create database treinamento;
    quit

O comando a seguir cria um novo projeto Laravel na pasta treinamento, baixando a estrutura básica do framework e instalando todas as dependências necessárias via Composer, garantindo que o ambiente esteja pronto para o desenvolvimento:

    composer create-project laravel/laravel treinamento
    cd treinamento
    php artisan serve

# MVC

Uma rota é a forma como o framework define e gerencia URLs para acessar diferentes partes da aplicação. As rotas são configuradas no arquivo routes/web.php (para páginas web) ou routes/api.php (para APIs) e determinam qual código será executado quando um usuário acessa uma URL específica. Exemplo:

    Route::get('/exemplo-de-rota', function () {
    echo "Uma rota sem controller, not good!";
    });

O controller é uma classe responsável por organizar a lógica da aplicação, separando as regras de negócio das rotas. Em vez de definir toda a lógica diretamente nas rotas, os controllers agrupam funcionalidades relacionadas, tornando o código mais limpo e modular. A convenção de nomenclatura para controllers segue o padrão PascalCase, onde o nome deve ser descritivo, no singular e sempre terminar com “Controller”, como ProdutoController ou UsuarioController. Vamos criar o EstagiarioController com o seguinte comando que gera automaticamente o arquivo correspondente dentro de app/Http/Controllers:

    php artisan make:controller EstagiarioController

A seguir criamos a rota estagiarios e a apontamos para o controller EstagiarioController, importando anteriormente o namespace App\Http\Controllers\EstagiarioController. O namespace é uma forma de organizar classes, funções e constantes para evitar conflitos de nomes em projetos grandes. Ele permite agrupar elementos relacionados dentro de um mesmo escopo, facilitando a reutilização e manutenção do código.

    use App\Http\Controllers\EstagiarioController;
    Route::get('/estagiarios', [EstagiarioController::class,'index']);

A camada View é responsável por exibir a interface da aplicação, separando a lógica de apresentação da lógica de negócio (controller). Ela utiliza o Blade, uma linguagem de templates que permite criar páginas dinâmicas de forma eficiente. As views ficam armazenadas na pasta resources/views e podem ser retornadas a partir de um controller usando return view('nome_da_view').

    mkdir resources/views/estagiarios
    touch resources/views/estagiarios/index.blade.php

No controller:

    public function index()
    {
        return view('estagiarios.index');
    }

Conteúdo mínimo de index.blade.php:

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

O Model é uma representação de uma tabela no banco de dados e é responsável pela interação com os dados dessa tabela. Ele encapsula a lógica de acesso e manipulação dos dados, permitindo realizar operações como inserção, atualização, exclusão e leitura de registros de forma simples e intuitiva. O Laravel usa o Eloquent ORM (Object-Relational Mapping) para mapear os dados do banco de dados para objetos PHP, o que permite que você trabalhe com as tabelas como se fosse uma classe de objetos.

Criando o model chamado Estagiario:

    php artisan make:model Estagiario -m

Acrescente ao model o seguinte método:

    protected $fillable = ['nome', 'email', 'idade'];

As migrations são uma forma de versionar e gerenciar o esquema do banco de dados, permitindo criar, alterar e remover tabelas de forma controlada e rastreável. Elas funcionam como um histórico de mudanças no banco de dados, ajudando a manter o controle de versões entre diferentes ambientes de desenvolvimento e produção.

Cada migration é uma classe PHP que define as operações a serem realizadas no banco de dados. As migrations são armazenadas na pasta database/migrations. As migrations tornam o processo de gerenciamento do banco de dados mais organizado e flexível, principalmente em projetos com múltiplos desenvolvedores. Vamos colocar três colunas para o model Estagiario: nome, idade e email.

    $table->string('nome');
    $table->string('email');
    $table->integer('idade');

# Desafio

   Crie uma rota chamada estagiarios/create apontando para o método create em EstagiarioController, que também deve ser criado.

No método create do EstagiarioController, insira os estagiários:

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

**Dica**

Toda vez que a rota estagiarios/create for acessada os cadastros serão realizados, pode-se deletar tudo antes das inserções com a função: App\Models\Estagiario::truncate()

Faça a migração para o banco de dados.

    php artisan migrate

Por fim, na view da index podemos buscar os estagiários cadastrados e passar como uma variável para o template:

    public function index(){
        return view('estagiarios.index', [
            'estagiarios' => App\Models\Estagiario::all()
        ]);
    }

No blade, listamos os estagiários:

    <ul>
        @foreach($estagiarios as $estagiario)
            <li>{{ $estagiario->nome }} - {{ $estagiario->email }} - {{ $estagiario->idade }} anos</li>
        @endforeach
    </ul>

# Exercício 1 - Importação de Dados e Estatísticas com Laravel

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

Dica: Você pode usar a classe League\Csv\Reader (disponível via Composer) para facilitar a leitura do CSV.

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

   Edite a view stats,blade.php:

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


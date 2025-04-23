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
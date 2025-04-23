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
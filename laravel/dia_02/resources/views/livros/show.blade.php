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
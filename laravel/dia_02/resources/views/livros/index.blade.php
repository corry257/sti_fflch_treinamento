@forelse($livros as $livro)
    <ul>
        <li><a href="/livros/{{$livro->id}}">{{ $livro->titulo }}</a></li>
        <li>{{ $livro->autor }}</li>
        <li>{{ $livro->isbn }}</li>
        <li>
            <form action="/livros/{{ $livro->id }} " method="post">
            @csrf
            @method('delete')
            <button type="submit" onclick="return confirm('Tem certeza?');">Apagar</button>
            </form>
        </li>
    </ul>
@empty
    Não há livros cadastrados
@endforelse
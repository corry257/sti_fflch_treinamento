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
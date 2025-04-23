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
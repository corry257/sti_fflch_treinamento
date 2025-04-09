<form method="POST" action="/livros">
    @csrf
    Título: <input type="text" name="titulo">
    Autor: <input type="text" name="autor">
    ISBN: <input type="text" name="isbn">
    <button type="submit">Enviar</button>
</form>
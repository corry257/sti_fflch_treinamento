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
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
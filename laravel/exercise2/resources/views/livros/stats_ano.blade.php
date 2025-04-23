<!DOCTYPE html>
<html>
<head>
    <title>Livros por Ano</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; }
        th, td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Livros por Ano</h1>
    
    <a href="/livros/stats">← Voltar</a>
    
    <table style="margin-top: 20px;">
        <tr>
            <th>Ano</th>
            <th>Quantidade</th>
        </tr>
        @foreach($porAno as $item)
        <tr>
            <td>{{ $item->ano ?? '-' }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Estatísticas</h1>
    
    <table>
        <tr>
            <th>exercise.csv</th>
            <th>rest</th>
            <th>walking</th>
            <th>running</th>
        </tr>
        <tr>
            <td>Qtde linhas</td>
            <td>{{ $dados['rest']['quantidade'] }}</td>
            <td>{{ $dados['walking']['quantidade'] }}</td>
            <td>{{ $dados['running']['quantidade'] }}</td>
        </tr>
        <tr>
            <td>Média Pulse</td>
            <td>{{ round($dados['rest']['media_pulse'], 1) }}</td>
            <td>{{ round($dados['walking']['media_pulse'], 1) }}</td>
            <td>{{ round($dados['running']['media_pulse'], 1) }}</td>
        </tr>
    </table>
</body>
</html>

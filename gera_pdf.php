<?php
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

// Conexão com banco
$conn = new mysqli("localhost", "root", "", "setembro_amarelo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Busca os participantes ordenados pelo id (ordem de cadastro)
$result = $conn->query("SELECT * FROM inscricoes ORDER BY id ASC");

// Monta o HTML para o PDF
$html = '
<style>
    table { 
        width: 100%; 
        border-collapse: collapse; 
        font-family: Arial, sans-serif; 
        font-size: 9px; 
    }
    th, td { 
        border: 1px solid #000; 
        padding: 5px; 
        word-wrap: break-word; 
        max-width: 90px; 
        vertical-align: top;
        text-align: left;
    }
    th { 
        background-color: #d4af37; 
        color: #000; 
    }
    h1 {
        text-align: center; 
        color: #000000ff; 
        margin-bottom: 20px;
        font-size: 16px;
    }
    /* Centraliza coluna Nº */
    th:first-child, td:first-child {
        width: 30px;
        text-align: center;
    }
</style>

<h1>Lista de Participantes</h1>
<table>
    <thead>
        <tr>
            <th>Nº</th>
            <th>Nome</th>
            <th>Nasc.</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Bairro</th>
            <th>Aluno Estácio</th>
            <th>Comorbidade</th>
            <th>Gênero</th>
            <th>Indicação</th>
            <th>Nome do Indicador</th>
            <th>Matrícula do Indicador</th>
        </tr>
    </thead>
    <tbody>
';

$contador = 1; // contador inicia em 1

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td style="text-align:center;">' . $contador . '</td>
        <td>' . htmlspecialchars($row['nome']) . '</td>
        <td>' . htmlspecialchars($row['nascimento']) . '</td>
        <td>' . htmlspecialchars($row['email']) . '</td>
        <td>' . htmlspecialchars($row['telefone']) . '</td>
        <td>' . htmlspecialchars($row['bairro']) . '</td>
        <td>' . htmlspecialchars($row['aluno_estacio']) . '</td>
        <td>' . htmlspecialchars($row['comorbidade']) . '</td>
        <td>' . htmlspecialchars($row['genero']) . '</td>
        <td>' . htmlspecialchars($row['indicacao']) . '</td>
        <td>' . htmlspecialchars($row['nome_indicador']) . '</td>
        <td>' . htmlspecialchars($row['matricula_indicador']) . '</td>
    </tr>';
    $contador++;
}

$html .= '
    </tbody>
</table>
';

$conn->close();

// Inicializa Dompdf
$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'landscape');
$dompdf->loadHtml($html);
$dompdf->render();

// Envia o PDF para o navegador
$dompdf->stream("lista_participantes.pdf", ["Attachment" => false]);
exit;
?>

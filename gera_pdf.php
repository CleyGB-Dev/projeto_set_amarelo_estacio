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
        font-size: 10px; 
    }
    th, td { 
        border: 1px solid #000; 
        padding: 6px; 
        word-wrap: break-word; 
        max-width: 80px; 
        vertical-align: top;
    }
    th { 
        background-color: #d4af37; 
        color: #000; 
    }
    h1 {
        text-align: center; 
        color: #d4af37; 
        margin-bottom: 20px;
    }
</style>

<h1>Lista de Participantes</h1>
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Data Nasc.</th>
            <th>Email</th>
            <th>Bairro</th>
            <th>Aluno?</th>
            <th>Comorb.</th>
            <th>Gênero</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['nome']) . '</td>
        <td>' . htmlspecialchars($row['nascimento']) . '</td>
        <td>' . htmlspecialchars($row['email']) . '</td>
        <td>' . htmlspecialchars($row['bairro']) . '</td>
        <td>' . htmlspecialchars($row['aluno_estacio']) . '</td>
        <td>' . htmlspecialchars($row['comorbidade']) . '</td>
        <td>' . htmlspecialchars($row['genero']) . '</td>
    </tr>';
}

$html .= '
    </tbody>
</table>
';

$conn->close();

// Inicializa Dompdf
$dompdf = new Dompdf();

// Define papel A4 paisagem
$dompdf->setPaper('A4', 'landscape');

$dompdf->loadHtml($html);
$dompdf->render();

// Envia o PDF para o navegador (abre para download ou visualização)
$dompdf->stream("lista_participantes.pdf", ["Attachment" => false]);
exit;
?>

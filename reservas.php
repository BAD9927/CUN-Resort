<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'cunresort';
$username = 'root';
$password = '';

try {

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $documento = $_POST['documento'];
    $tipo_documento = $_POST['tipo_documento'];
    $nombre = $_POST['nombre'];
    $celular = $_POST['celular'];
    $email = $_POST['email'];
    $habitacion = $_POST['habitacion'];

    // Validar que todos los campos estén completos
    if (!empty($documento) && !empty($tipo_documento) && !empty($nombre) && !empty($celular) && !empty($email) && !empty($habitacion)) {
        // Preparar y ejecutar la consulta SQL
        $sql = "INSERT INTO reservas (documento, tipo_documento, nombre, celular, email, habitacion) VALUES (:documento, :tipo_documento, :nombre, :celular, :email, :habitacion)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':habitacion', $habitacion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Registro realizado exitosamente.";
        } else {
            echo "Error al registrar la información.";
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}
?>

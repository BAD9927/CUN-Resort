<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'cunresort';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Funciones CRUD
function createReserva($conn, $data) {
    $sql = "INSERT INTO reservas (documento, tipo_documento, nombre, celular, email, habitacion) VALUES (:documento, :tipo_documento, :nombre, :celular, :email, :habitacion)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}

function readReservas($conn) {
    $sql = "SELECT * FROM reservas";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateReserva($conn, $data) {
    $sql = "UPDATE reservas SET documento = :documento, tipo_documento = :tipo_documento, nombre = :nombre, celular = :celular, email = :email, habitacion = :habitacion WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}

function deleteReserva($conn, $id) {
    $sql = "DELETE FROM reservas WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

// Lógica para manejar las solicitudes CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $data = [
            ':documento' => $_POST['documento'],
            ':tipo_documento' => $_POST['tipo_documento'],
            ':nombre' => $_POST['nombre'],
            ':celular' => $_POST['celular'],
            ':email' => $_POST['email'],
            ':habitacion' => $_POST['habitacion']
        ];
        createReserva($conn, $data);
        echo "Registro creado exitosamente.";
    } elseif ($action === 'update') {
        $data = [
            ':id' => $_POST['id'],
            ':documento' => $_POST['documento'],
            ':tipo_documento' => $_POST['tipo_documento'],
            ':nombre' => $_POST['nombre'],
            ':celular' => $_POST['celular'],
            ':email' => $_POST['email'],
            ':habitacion' => $_POST['habitacion']
        ];
        updateReserva($conn, $data);
        echo "Registro actualizado exitosamente.";
    } elseif ($action === 'delete') {
        deleteReserva($conn, $_POST['id']);
        echo "Registro eliminado exitosamente.";
    }
}

// Obtener todos los registros para mostrarlos
$reservas = readReservas($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Reservas - Hotel CUN Resort</title>
    <link rel="stylesheet" href="../Vista/style.css">

</head>
    <header>
<body>
        <nav>
            <div class="logo">Hotel CUN Resort</div>
            <ul>
                <li><a href="index.html" class="active">Inicio</a></li>
                <li><a href="quienes-somos.html">Quiénes Somos</a></li>
                <li><a href="servicios.html">Servicios</a></li>
                <li><a href="contacto.html">Contáctenos</a></li>
                <li><a href="administracion-db.php">Administración</a></li>
            </ul>
        </nav>
    </header>

    <main class="about-section">
        <section class="about-content">
            <h2 class="form-title">Administración de Reservas</h2>

            <!-- Formulario para crear y actualizar reservas -->
            <form method="POST" class="registro-form">
                <input type="hidden" name="id" id="id">

                <div class="form-group">
                    <label for="documento">Documento:</label>
                    <input type="text" name="documento" id="documento" required>
                </div>

                <div class="form-group">
                    <label for="tipo_documento">Tipo Documento:</label>
                    <input type="text" name="tipo_documento" id="tipo_documento" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>

                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="text" name="celular" id="celular" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="habitacion">Habitación:</label>
                    <input type="text" name="habitacion" id="habitacion" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" name="action" value="create" class="cta-button">Crear</button>
                    <button type="submit" name="action" value="update" class="cta-button">Actualizar</button>
                </div>
            </form>

            <h2 class="form-title">Listado de Reservas</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Documento</th>
                            <th>Tipo Documento</th>
                            <th>Nombre</th>
                            <th>Celular</th>
                            <th>Email</th>
                            <th>Habitación</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reserva['id']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['documento']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['tipo_documento']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['celular']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['email']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['habitacion']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['fecha_registro']); ?></td>
                                <td class="action-buttons">
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                        <button type="submit" name="action" value="delete" class="delete-button">Eliminar</button>
                                    </form>
                                    <button onclick="fillForm(<?php echo htmlspecialchars(json_encode($reserva)); ?>)" class="edit-button">Editar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

    </main>

    <footer>
        <p>&copy; 2024 Hotel CUN Resort - Administración de Reservas</p>
    </footer>

    <script>
        function fillForm(data) {
            document.getElementById('id').value = data.id;
            document.getElementById('documento').value = data.documento;
            document.getElementById('tipo_documento').value = data.tipo_documento;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('celular').value = data.celular;
            document.getElementById('email').value = data.email;
            document.getElementById('habitacion').value = data.habitacion;
        }
    </script>
</body>
</html>

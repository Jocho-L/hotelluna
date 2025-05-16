<!-- Cada vista debe iniciar con esta plantilla -->
<!-- ZONA: Cabecera -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Inicio</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Starter Page</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- ZONA: Contenido -->
<div class="content">
  <div class="container-fluid">
    <div class="row">

      <div class="container mt-5">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Registrar Nueva Reserva</h3>
          </div>
          <form action="guardar_reserva.php" method="POST">
            <div class="card-body">
              <!-- Selección de Cliente -->
              <div class="form-group">
                <label for="idcliente">Cliente</label>
                <select class="form-control" id="idcliente" name="idcliente" required>
                  <option value="">Seleccione un cliente</option>
                  <!-- Aquí deberías cargar los clientes desde la base de datos -->
                  <option value="1">Cliente 1</option>
                  <option value="2">Cliente 2</option>
                </select>
              </div>

              <!-- Selección de Habitación -->
              <div class="form-group">
                <label for="idhabitacion">Habitación</label>
                <select class="form-control" id="idhabitacion" name="idhabitacion" required>
                  <option value="">Seleccione una habitación</option>
                  <!-- Aquí deberías cargar las habitaciones desde la base de datos -->
                  <option value="1">Habitación 101</option>
                  <option value="2">Habitación 102</option>
                </select>
              </div>

              <!-- Fecha y Hora de Inicio -->
              <div class="form-group">
                <label for="fechahorainicio">Fecha y Hora de Inicio</label>
                <input type="datetime-local" class="form-control" id="fechahorainicio" name="fechahorainicio" required>
              </div>

              <!-- Fecha y Hora de Fin -->
              <div class="form-group">
                <label for="fechahorafin">Fecha y Hora de Fin</label>
                <input type="datetime-local" class="form-control" id="fechahorafin" name="fechahorafin" required>
              </div>

              <!-- Valor del Alquiler -->
              <div class="form-group">
                <label for="valoralquiler">Valor del Alquiler</label>
                <input type="number" class="form-control" id="valoralquiler" name="valoralquiler" step="0.01" required>
              </div>

              <!-- Modalidad de Pago -->
              <div class="form-group">
                <label for="modalidadpago">Modalidad de Pago</label>
                <select class="form-control" id="modalidadpago" name="modalidadpago" required>
                  <option value="Efectivo">Efectivo</option>
                  <option value="Tarjeta">Tarjeta</option>
                  <option value="Transferencia">Transferencia</option>
                </select>
              </div>

              <!-- Número de Transacción (opcional) -->
              <div class="form-group">
                <label for="numtransaccion">Número de Transacción (opcional)</label>
                <input type="text" class="form-control" id="numtransaccion" name="numtransaccion">
              </div>

              <!-- Lugar de Procedencia (opcional) -->
              <div class="form-group">
                <label for="lugarprocedencia">Lugar de Procedencia (opcional)</label>
                <input type="text" class="form-control" id="lugarprocedencia" name="lugarprocedencia">
              </div>

              <!-- Observaciones -->
              <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
              </div>

              <!-- Colaborador de Entrada -->
              <div class="form-group">
                <label for="idcolaboradorentrada">Colaborador de Entrada</label>
                <select class="form-control" id="idcolaboradorentrada" name="idcolaboradorentrada" required>
                  <option value="">Seleccione un colaborador</option>
                  <!-- Aquí deberías cargar los colaboradores desde la base de datos -->
                  <option value="1">Colaborador 1</option>
                  <option value="2">Colaborador 2</option>
                </select>
              </div>

              <!-- Colaborador de Salida (opcional) -->
              <div class="form-group">
                <label for="idcolaboradorsalida">Colaborador de Salida</label>
                <select class="form-control" id="idcolaboradorsalida" name="idcolaboradorsalida">
                  <option value="">Seleccione un colaborador</option>
                  <option value="1">Colaborador 1</option>
                  <option value="2">Colaborador 2</option>
                </select>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Registrar Reserva</button>
              <button type="reset" class="btn btn-secondary">Cancelar</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

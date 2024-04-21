<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">
        Información del Evento
    </legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre del Evento</label>
        <input type="text"
         class="formulario__input"
         id="nombre"
         name="nombre"
         placeholder="Nombre del Evento"
         value="<?php echo $evento->nombre?>">
    </div>

    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Descripcion</label>
        <textarea class="formulario__input"
         id="descripcion"
         name="descripcion"
         placeholder="Descripcion del Evento"
         rows="5"
         ><?php echo $evento->descripcion;?></textarea>
    </div>

    <div class="formulario__campo">
        <label for="categoria" class="formulario__label">Categoria o tipo de Evento</label>
        <select name="categoria_id" id="categoria" class="formulario__select">
            <option value="">-- Seleccionar --</option>
            <?php foreach($categorias as $categoria){?>
                <option <?php echo $evento->categoria_id === $categoria->id ? 'selected' : '';?> 
                value="<?php echo $categoria->id;?>"><?php echo $categoria->nombre;?></option>
            <?php };?>
        </select>
    </div>

    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Selecciona el Día</label>
        <div class="formulario__radio">
            <?php foreach($dias as $dia){?>
                <div>
                    <label for="<?php echo strtolower(($dia->nombre));?>"><?php echo $dia->nombre;?></label>
                    <input type="radio"
                    id="<?php echo strtolower(($dia->nombre));?>"
                    name="dia"
                    value="<?php echo $dia->id;?>">
                </div>
            <?php  };?>
        </div>
    </div>

    <div id="horas" class="formulario__campo">
        <label for="" class="formulario__label">Seleccionar Hora</label>
        <ul class="horas">
            <?php foreach($horas as $hora){?>
                <li class="horas__hora"><?php echo $hora->hora;?></li>
           <?php };?>
        </ul>
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">
        Información Extra
    </legend>
    
    <div class="formulario__campo">
        <label for="ponente" class="formulario__label">Ponente</label>
        <input type="text"
         class="formulario__input"
         id="ponente"
         placeholder="Buscar Ponente"
         >
    </div>

    <div class="formulario__campo">
        <label for="disponibles" class="formulario__label">Lugares Disponibles</label>
        <input type="number"
        min="1"
         class="formulario__input"
         id="disponibles"
         name="disponibles"
         placeholder="Ej. 20"
         value="<?php echo $evento->disponibles;?>">
    </div>

</fieldset>
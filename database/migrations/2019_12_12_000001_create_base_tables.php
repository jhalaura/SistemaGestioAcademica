<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles_educativos', function (Blueprint $table) {
            $table->unsignedInteger('id_nivel', true);
            $table->string('nombre');
            $table->string('descripcion')->nullable();
        });

        Schema::create('anios_lectivos', function (Blueprint $table) {
            $table->unsignedInteger('id_anio', true);
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->default(true);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedInteger('id_rol', true);
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
        });

        Schema::create('permisos', function (Blueprint $table) {
            $table->unsignedInteger('id_permiso', true);
            $table->string('codigo');
            $table->string('descripcion');
            $table->string('modulo');
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->unsignedInteger('id_usuario', true);
            $table->unsignedInteger('id_rol');
            $table->string('nombre');
            $table->string('apellido');
            $table->text('email_cifrado')->nullable();
            $table->string('email_hash')->nullable();
            $table->string('password_hash');
            $table->string('telefono')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('estado')->default('activo');
            $table->integer('intentos_fallidos')->default(0);
            $table->dateTime('bloqueado_hasta')->nullable();
            $table->string('token_recuperacion')->nullable();
            $table->dateTime('token_expira_en')->nullable();
            $table->string('totp_secreto')->nullable();
            $table->dateTime('ultimo_acceso')->nullable();
            $table->string('ip_ultimo_acceso')->nullable();
            $table->timestamps();

            $table->foreign('id_rol')->references('id_rol')->on('roles');
        });

        Schema::create('cursos', function (Blueprint $table) {
            $table->unsignedInteger('id_curso', true);
            $table->unsignedInteger('id_nivel');
            $table->unsignedInteger('id_anio');
            $table->string('nombre');
            $table->string('grado')->nullable();
            $table->string('seccion')->nullable();
            $table->integer('capacidad');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_educativos');
            $table->foreign('id_anio')->references('id_anio')->on('anios_lectivos');
        });

        Schema::create('docentes', function (Blueprint $table) {
            $table->unsignedInteger('id_docente', true);
            $table->unsignedInteger('id_usuario');
            $table->string('codigo_docente')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('titulo_academico')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });

        Schema::create('materias', function (Blueprint $table) {
            $table->unsignedSmallInteger('id_materia', true);
            $table->unsignedInteger('id_nivel');
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->integer('horas_semanales');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_educativos');
        });

        Schema::create('tipos_evaluacion', function (Blueprint $table) {
            $table->unsignedInteger('id_tipo', true);
            $table->string('nombre');
            $table->float('ponderacion');
            $table->boolean('activo')->default(true);
        });

        Schema::create('estudiantes', function (Blueprint $table) {
            $table->unsignedInteger('id_estudiante', true);
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_curso');
            $table->string('codigo_estudiante')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero');
            $table->text('direccion_cifrada')->nullable();
            $table->text('observaciones_cifradas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_curso')->references('id_curso')->on('cursos');
        });

        Schema::create('padres_familia', function (Blueprint $table) {
            $table->unsignedInteger('id_padre', true);
            $table->unsignedInteger('id_usuario');
            $table->string('parentesco');
            $table->string('ocupacion')->nullable();
            $table->text('documento_identidad_cifrado')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });

        Schema::create('periodos_evaluacion', function (Blueprint $table) {
            $table->unsignedInteger('id_periodo', true);
            $table->unsignedInteger('id_anio');
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->default(true);

            $table->foreign('id_anio')->references('id_anio')->on('anios_lectivos');
        });

        Schema::create('asignaciones', function (Blueprint $table) {
            $table->unsignedInteger('id_asignacion', true);
            $table->unsignedInteger('id_docente');
            $table->unsignedSmallInteger('id_materia');
            $table->unsignedInteger('id_curso');
            $table->unsignedInteger('id_anio');
            $table->boolean('activo')->default(true);

            $table->foreign('id_docente')->references('id_docente')->on('docentes');
            $table->foreign('id_materia')->references('id_materia')->on('materias');
            $table->foreign('id_curso')->references('id_curso')->on('cursos');
            $table->foreign('id_anio')->references('id_anio')->on('anios_lectivos');
        });

        Schema::create('geocercas', function (Blueprint $table) {
            $table->unsignedInteger('id_geocerca', true);
            $table->unsignedInteger('id_asignacion');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->float('latitud_centro');
            $table->float('longitud_centro');
            $table->float('radio_metros');
            $table->time('horario_inicio')->nullable();
            $table->time('horario_fin')->nullable();
            $table->string('dias_semana');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_asignacion')->references('id_asignacion')->on('asignaciones');
        });

        Schema::create('calificaciones', function (Blueprint $table) {
            $table->unsignedInteger('id_calificacion', true);
            $table->unsignedInteger('id_estudiante');
            $table->unsignedInteger('id_asignacion');
            $table->unsignedInteger('id_periodo');
            $table->unsignedInteger('id_tipo_eval');
            $table->float('nota');
            $table->float('nota_maxima');
            $table->date('fecha_evaluacion');
            $table->text('observacion')->nullable();
            $table->unsignedInteger('ingresado_por');
            $table->timestamps();

            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes');
            $table->foreign('id_asignacion')->references('id_asignacion')->on('asignaciones');
            $table->foreign('id_periodo')->references('id_periodo')->on('periodos_evaluacion');
            $table->foreign('id_tipo_eval')->references('id_tipo')->on('tipos_evaluacion');
            $table->foreign('ingresado_por')->references('id_usuario')->on('usuarios');
        });

        Schema::create('asistencia', function (Blueprint $table) {
            $table->unsignedInteger('id_asistencia', true);
            $table->unsignedInteger('id_estudiante');
            $table->unsignedInteger('id_asignacion');
            $table->unsignedInteger('id_geocerca');
            $table->date('fecha');
            $table->dateTime('hora_registro');
            $table->string('estado');
            $table->float('latitud_registro');
            $table->float('longitud_registro');
            $table->float('distancia_metros');
            $table->boolean('dentro_geocerca');
            $table->string('dispositivo_origen');
            $table->string('justificacion_url')->nullable();
            $table->text('observacion')->nullable();
            $table->unsignedInteger('registrado_por');

            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes');
            $table->foreign('id_asignacion')->references('id_asignacion')->on('asignaciones');
            $table->foreign('id_geocerca')->references('id_geocerca')->on('geocercas');
            $table->foreign('registrado_por')->references('id_usuario')->on('usuarios');
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->unsignedInteger('id_notificacion', true);
            $table->unsignedInteger('id_usuario_destino');
            $table->unsignedInteger('id_usuario_origen')->nullable();
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('tipo');
            $table->string('canal');
            $table->boolean('leido')->default(false);
            $table->dateTime('fecha_lectura')->nullable();
            $table->string('entidad_tipo')->nullable();
            $table->integer('entidad_id')->nullable();

            $table->foreign('id_usuario_destino')->references('id_usuario')->on('usuarios');
            $table->foreign('id_usuario_origen')->references('id_usuario')->on('usuarios');
        });

        Schema::create('estudiante_tutor', function (Blueprint $table) {
            $table->unsignedInteger('id_estudiante');
            $table->unsignedInteger('id_padre');
            $table->boolean('es_contacto_principal')->default(false);

            $table->primary(['id_estudiante', 'id_padre']);
            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes');
            $table->foreign('id_padre')->references('id_padre')->on('padres_familia');
        });

        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->unsignedInteger('id_rol');
            $table->unsignedInteger('id_permiso');

            $table->primary(['id_rol', 'id_permiso']);
            $table->foreign('id_rol')->references('id_rol')->on('roles');
            $table->foreign('id_permiso')->references('id_permiso')->on('permisos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
        Schema::dropIfExists('estudiante_tutor');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('asistencia');
        Schema::dropIfExists('calificaciones');
        Schema::dropIfExists('geocercas');
        Schema::dropIfExists('asignaciones');
        Schema::dropIfExists('periodos_evaluacion');
        Schema::dropIfExists('padres_familia');
        Schema::dropIfExists('estudiantes');
        Schema::dropIfExists('tipos_evaluacion');
        Schema::dropIfExists('materias');
        Schema::dropIfExists('docentes');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('permisos');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('anios_lectivos');
        Schema::dropIfExists('niveles_educativos');
    }
};

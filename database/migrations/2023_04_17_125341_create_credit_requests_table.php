<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_requests', function (Blueprint $table) {
            $table->id();
            $table->text("nombre")->nullable();
            $table->text("apellido_paterno")->nullable();
            $table->text("apellido_materno")->nullable();
            $table->text("fecha_nacimiento")->nullable();
            $table->text("nacionalidad")->nullable();
            $table->text("correo")->nullable();
            $table->text("celular")->nullable();
            $table->text("telefono")->nullable();
            $table->text("file_comprobante_ine_identificacion")->nullable();
            $table->text("text_comprobante_ine_identificacion")->nullable();
            $table->text("tipo_identificacion_social")->nullable();
            $table->text("numero_curp")->nullable();
            $table->text("file_comprobante_domicilio_cliente")->nullable();
            $table->text("direccion_domicilio")->nullable();
            $table->text("relacion_per_del_titular_del_compro_domicilio_cliente")->nullable();
            $table->text("relacion_per_del_titular_del_compro_domicilio_domicilio")->nullable();
            $table->text("file_caratula_del_estado_de_cuenta")->nullable();
            $table->text("principal_fuente_de_ingreso_como_piensa_pagar_lo_solicitado")->nullable();
            $table->text("cual_es_producto_que_esta_solicitando")->nullable();
            $table->text("escala_de_referencia_gerente_de_cliente_ola")->nullable();
            $table->text("justificar_la_referencia")->nullable();
            $table->text("quien_lo_refiere")->nullable();
            $table->text("desde_cuando_lo_conoce_por_que_lo_refiere")->nullable();
            $table->text("tiene_redes_sociales")->nullable();
            $table->text("ciudad")->nullable();
            $table->text("monto_de_dinero_solicitado")->nullable();
            $table->text("razon_del_prestamo")->nullable();
            $table->text("hace_cuanto_tiempo_debe_ese_dinero")->nullable();
            $table->text("a_que_institucion_le_debe_ese_dinero")->nullable();
            $table->text("cuenta_con_una_cuenta_bancaria")->nullable();
            $table->text("respuesta_de_cuenta_con_una_cuenta_bancaria")->nullable();
            $table->text("numero_de_cuenta_clabe")->nullable();
            $table->text("numero_de_cuenta")->nullable();
            $table->text("file_foto_de_tarjeta")->nullable();
            $table->text("por_que_no_ha_popido_pagar_su_deuda")->nullable();
            $table->text("nombre_apellido_del_obligado_solidario")->nullable();
            $table->text("telefono_obligado_solidario")->nullable();
            $table->text("correo_obligado_solidario")->nullable();
            $table->text("nacionalidad_obligado_solidario")->nullable();
            $table->text("tipo_de_identificacion_oficial")->nullable();
            $table->text("file_comprobante_ine_o_identificacion_oficial")->nullable();
            $table->text("ine_o_identificacion_oficial")->nullable();
            $table->text("numero_curp_obligado")->nullable();
            $table->text("file_comprobante_de_domicilio")->nullable();
            $table->text("domicilio")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_requests');
    }
};

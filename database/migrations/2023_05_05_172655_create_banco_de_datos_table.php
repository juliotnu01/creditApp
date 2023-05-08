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
        Schema::create('banco_de_datos', function (Blueprint $table) {
            $table->id();
            
            $table->text('apellido_materno')->nullable();
            $table->text('apellido_paterno')->nullable();
            $table->text('aprobacion_legal')->nullable();
            $table->text('aprobacion_operativa')->nullable();
            $table->text('ciudad')->nullable();
            $table->text('colaborador_ola_o_cliente_ola_que_lo_referencia')->nullable();
            $table->text('comprobacion_domicilio_del_cliente')->nullable();
            $table->text('correo_electronico_del_solicitante')->nullable();
            $table->text('desde_cuando_lo_conoce_porquer_refiere')->nullable();
            $table->text('direccion')->nullable();
            $table->text('el_local_de_su_negocio_es_restado_o_propiedad_del_solicitante')->nullable();
            $table->text('escala_de_referencia_gerente_de_cliente_ola')->nullable();
            $table->text('fecha_de_nacimiento')->nullable();
            $table->text('ine_o_identificacion_oficial')->nullable();
            $table->text('marca_temporal')->nullable();
            $table->text('nombre_completo')->nullable();
            $table->text('nombres')->nullable();
            $table->text('nombre_de_supervisort_ola')->nullable();
            $table->text('numero_de_contacto_ws_solicitante')->nullable();
            $table->text('monto_dinero_solicitado')->nullable();
            $table->text('foto_del_local')->nullable();
            $table->text('quien_lo_refiere_que_tipo_de_relacion_tiene')->nullable();
            $table->text('si_no_tiene_cuenta_imagen_de_la_cuenta_del_banco')->nullable();
            $table->text('si_respuesta_fue_si_en_banco_tiene_cuenta')->nullable();
            $table->text('tiene_redes_sociales')->nullable();
            $table->text('a_que_instucion_le_debe_dinero')->nullable();
            $table->text('cuenta_con_una_cuenta_bancaria')->nullable();
            $table->text('cual_es_el_numero_de_su_cuenta_clabe')->nullable();
            $table->text('cual_es_el_numero_de_su_cuenta_tarjeta')->nullable();
            $table->text('cual_es_el_producto_que_se_solicitando')->nullable();
            $table->text('cual_es_la_razon_por_la_que_tiene_esa_deuda')->nullable();
            $table->text('cual_es_el_principal_fuente_de_ingreso_o_como_piensa_pagar')->nullable();
            $table->text('hace_cuanto_tiene_ese_dinero')->nullable();
            $table->text('para_que_necesita_el_capital')->nullable();
            $table->text('porque_no_ha_podido_pagar_su_deuda')->nullable();
            $table->text('sus_proveedores_pueden_emitir_factura')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banco_de_datos');
    }
};

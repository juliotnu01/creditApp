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
            $table->text('nombre_supervisor_ola')->nullable();// Nombre de supervisor OLA 	
            $table->text('ciudad')->nullable();// Ciudad 
            $table->text('nombre_completo')->nullable();// NOMBRE COMPLETO	
            $table->text('fecha_de_nacimiento')->nullable();// Fecha de nacimiento	
            $table->text('direccion')->nullable();// Dirección 	
            $table->text('ine_o_identificacion_oficial')->nullable();// INE o identificación oficial	
            $table->text('tipo_de_relacion_persona_titular_comprobante_domicilio_cliente')->nullable();// Tipo de relación con la persona titular del comprobante de domicilio (del cliente)	
            $table->text('comprobante_domicilio_cliente')->nullable();// Comprobante de domicilio del cliente	
            $table->text('comprobante_domicilio_alterno')->nullable();// Comprobante de domicilio alterno	
            $table->text('tipo_de_relacion_persona_titular_comprobante_domicilio_alterno')->nullable();// Tipo de relación con la persona titular del comprobante de domicilio (del domicilio alterno)	
            $table->text('principal_fuente_de_ingreso')->nullable();// ¿Cuál es su principal fuente de ingreso? o ¿Cómo piensa pagar lo que está solicitando?	
            $table->text('producto_solicitado')->nullable();// ¿Cuál es el producto que se está solicitando?
            $table->text('dinero_solicitado')->nullable();// Por favor indica el monto de dinero solicitado	
            $table->text('razon_credito')->nullable();// ¿Cuál es la razón de tu crédito?	
            $table->text('en_que_tiempo_piensa_pagar')->nullable();// ¿En cuánto tiempo piensas pagar tu crédito?	
            $table->text('bajo_que_esquema_esta_acostumbrado_a_pagar')->nullable();// ¿Bajo qué esquema está acostumbrado(a) pagar sus préstamos?
            $table->text('para_que_necesita_el_dinero')->nullable();// ¿Para qué necesita el capital?	
            $table->text('cuenta_con_una_cuenta_bancaria')->nullable();// ¿Cuenta con una cuenta bancaria?	
            $table->text('banco_cuenta_bancaria')->nullable();// Si tu respuesta fue que SI, ¿en qué banco tiene cuenta?	
            $table->text('numero_de_cuenta_clabe')->nullable();// ¿Cuál es el número de cuenta CLABE?	
            $table->text('numero_de_cuenta_tarjeta')->nullable();// ¿Cuál es el número de su cuenta/tarjeta?	
            $table->text('si_no_tiene_tarjeta_o_cable_indique_tarjeta_de_banco')->nullable();// Si no tiene su número de cuenta o cuenta CLABE, por favor sube la imagen la tarjeta de banco.	
            $table->text('nombre_telefono_referencia_familia')->nullable();// Nombre y teléfono de una referencia familiar	
            $table->text('nombre_telefono_referencia_personal')->nullable();// Nombre y teléfono de una referencia personal	
            $table->text('comprobante_ingresos_certificado_laboral')->nullable();// Comprobante de ingresos, certificado laboral o contrato de trabajo (para maestros será aceptado talón de cheque con plaza)	
            $table->text('nombre_telefono_referencia_empresa')->nullable();// Nombre y teléfono de una referencia  en su empresa	
            $table->text('eres_trabajador_independiente')->nullable();// ¿Tú eres trabajador independiente?	
            $table->text('nombre_apellido_del_solidario_trabajador_independiente')->nullable();// Nombre y apellido del obligado solidario para Trabajador independiente 
            $table->text('telefono_del_solidario_trabajador_independiente')->nullable();// Teléfono del obligado solidario para Trabajador independiente 	
            $table->text('ine_identificacion_del_solidario_trabajador_independiente')->nullable();// INE o identificación oficial del obligado solidario para Trabajador independiente 
            $table->text('escala_referencia_gerente_ola')->nullable();// Escala de Referencia Gerente de Cliente OLA
            $table->text('correo_electronico_del_solicitante')->nullable();// Correo electrónico del solicitante	
            $table->text('numero_de_contacto_ws')->nullable();// Número de contacto (Whatsapp del solicitante)	
            $table->text('quien_lo_refiere_que_tipo_de_relacion_tiene')->nullable();// Quien lo refiere? Que tipo de  relación tiene (amistad, familia, vecino, etc)? 	
            $table->text('tiene_redes_sociales')->nullable();// Tiene redes sociales? Como lo encontramos en las Redes Sociales? (LinkedIn, Facebook, instagram, etc.)	
            $table->text('desde_cuando_lo_conoce_porque_lo_refiere')->nullable();// Desde cuando lo conoce? Porque lo Refiere? 
            $table->text('nombre_apellido_aval_subrogado_solidario_fallecimiento')->nullable();// Nombre y Apellido del Aval o subrogado solidario en caso de fallecimiento
            $table->text('caratula_del_estado_de_cuenta')->nullable();// Carátula del estado de cuenta
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // TRIGGER 1: Pago completado → estado_admision = 'En Proceso'
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_actualizar_estado_pago()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.estado_pago = 'Completado' THEN
                    UPDATE postulante
                    SET estado_admision = 'En Proceso'
                    WHERE id_postulante = NEW.id_postulante
                      AND estado_admision = 'Pendiente';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_pago_aprobado
            AFTER INSERT OR UPDATE ON pago
            FOR EACH ROW EXECUTE FUNCTION fn_actualizar_estado_pago();
        ");

        // TRIGGER 2: Opción 1 ≠ Opción 2 de carrera
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_validar_opciones_carrera()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.id_carrera_opcion1 = NEW.id_carrera_opcion2 THEN
                    RAISE EXCEPTION 'La primera y segunda opción de carrera no pueden ser la misma.';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_validar_opciones_carrera
            BEFORE INSERT OR UPDATE ON postulante
            FOR EACH ROW EXECUTE FUNCTION fn_validar_opciones_carrera();
        ");

        // TRIGGER 3: Edad mínima 16 años
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_validar_edad_postulante()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF EXTRACT(YEAR FROM age(NEW.fecha_nacimiento)) < 16 THEN
                    RAISE EXCEPTION 'El postulante debe ser mayor de 16 años.';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_validar_edad_postulante
            BEFORE INSERT OR UPDATE ON postulante
            FOR EACH ROW EXECUTE FUNCTION fn_validar_edad_postulante();
        ");

        // TRIGGER 4: Capacidad máxima del grupo (70)
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_verificar_capacidad_grupo()
            RETURNS TRIGGER AS \$\$
            DECLARE
                v_cantidad INTEGER;
                v_capacidad INTEGER;
            BEGIN
                SELECT COUNT(*) INTO v_cantidad
                FROM inscripcion_grupo
                WHERE id_grupo = NEW.id_grupo;

                SELECT capacidad_maxima INTO v_capacidad
                FROM grupo WHERE id_grupo = NEW.id_grupo;

                IF v_cantidad >= v_capacidad THEN
                    RAISE EXCEPTION 'El grupo alcanzó su capacidad máxima de % estudiantes.', v_capacidad;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_capacidad_grupo
            BEFORE INSERT ON inscripcion_grupo
            FOR EACH ROW EXECUTE FUNCTION fn_verificar_capacidad_grupo();
        ");

        // TRIGGER 5: Nota entre 0 y 100
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_validar_nota()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.calificacion > 100.00 OR NEW.calificacion < 0.00 THEN
                    RAISE EXCEPTION 'La calificación debe estar entre 0 y 100 puntos.';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_validar_nota
            BEFORE INSERT OR UPDATE ON nota
            FOR EACH ROW EXECUTE FUNCTION fn_validar_nota();
        ");

        // TRIGGER 6: Cupo por carrera al aprobar
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_verificar_cupo_carrera()
            RETURNS TRIGGER AS \$\$
            DECLARE
                v_admitidos INTEGER;
                v_cupo INTEGER;
            BEGIN
                IF NEW.estado_admision = 'Aprobado' AND OLD.estado_admision <> 'Aprobado' THEN
                    SELECT COUNT(*) INTO v_admitidos
                    FROM postulante
                    WHERE id_carrera_opcion1 = NEW.id_carrera_opcion1
                      AND estado_admision = 'Aprobado';

                    SELECT cupo_maximo INTO v_cupo
                    FROM carrera WHERE id_carrera = NEW.id_carrera_opcion1;

                    IF v_admitidos >= v_cupo THEN
                        RAISE EXCEPTION 'No existen cupos disponibles para esta carrera.';
                    END IF;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_verificar_cupo_carrera
            BEFORE UPDATE ON postulante
            FOR EACH ROW EXECUTE FUNCTION fn_verificar_cupo_carrera();
        ");

        // TRIGGER 7: Recalcular promedio final automáticamente
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_recalcular_promedio()
            RETURNS TRIGGER AS \$\$
            DECLARE
                v_promedio NUMERIC(5,2);
            BEGIN
                SELECT COALESCE(SUM(n.calificacion * (e.porcentaje_ponderado / 100.0)), 0)
                INTO v_promedio
                FROM nota n
                JOIN examen e ON n.id_examen = e.id_examen
                WHERE n.id_postulante = NEW.id_postulante;

                UPDATE postulante
                SET promedio_final = v_promedio
                WHERE id_postulante = NEW.id_postulante;

                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_recalcular_promedio
            AFTER INSERT OR UPDATE ON nota
            FOR EACH ROW EXECUTE FUNCTION fn_recalcular_promedio();
        ");

        // TRIGGER 8: Correo único en postulante
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_correo_unico_postulante()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF EXISTS(
                    SELECT 1 FROM postulante
                    WHERE correo = NEW.correo
                      AND id_postulante <> COALESCE(NEW.id_postulante, 0)
                ) THEN
                    RAISE EXCEPTION 'El correo % ya está registrado.', NEW.correo;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_correo_postulante
            BEFORE INSERT OR UPDATE ON postulante
            FOR EACH ROW EXECUTE FUNCTION fn_correo_unico_postulante();
        ");

        // TRIGGER 9: Límite de 4 grupos por docente por gestión
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_limite_grupos_docente()
            RETURNS TRIGGER AS \$\$
            DECLARE
                v_cantidad INTEGER;
            BEGIN
                SELECT COUNT(*) INTO v_cantidad
                FROM grupo
                WHERE id_docente = NEW.id_docente AND gestion = NEW.gestion;

                IF v_cantidad >= 4 THEN
                    RAISE EXCEPTION 'Un docente no puede tener más de 4 grupos en la misma gestión.';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_limite_grupos_docente
            BEFORE INSERT OR UPDATE ON grupo
            FOR EACH ROW EXECUTE FUNCTION fn_limite_grupos_docente();
        ");

        // TRIGGER 10: Estado Aprobado/Reprobado automático por promedio
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_estado_admision_automatico()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.promedio_final >= 60.00 THEN
                    NEW.estado_admision := 'Aprobado';
                ELSE
                    NEW.estado_admision := 'Reprobado';
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;

            CREATE TRIGGER tg_estado_admision_automatico
            BEFORE UPDATE OF promedio_final ON postulante
            FOR EACH ROW EXECUTE FUNCTION fn_estado_admision_automatico();
        ");
    }

    public function down(): void
    {
        $triggers = [
            ['tg_pago_aprobado',             'pago'],
            ['tg_validar_opciones_carrera',  'postulante'],
            ['tg_validar_edad_postulante',   'postulante'],
            ['tg_capacidad_grupo',           'inscripcion_grupo'],
            ['tg_validar_nota',              'nota'],
            ['tg_verificar_cupo_carrera',    'postulante'],
            ['tg_recalcular_promedio',       'nota'],
            ['tg_correo_postulante',         'postulante'],
            ['tg_limite_grupos_docente',     'grupo'],
            ['tg_estado_admision_automatico','postulante'],
        ];

        foreach ($triggers as [$trigger, $tabla]) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$trigger} ON {$tabla};");
        }

        $funciones = [
            'fn_actualizar_estado_pago',
            'fn_validar_opciones_carrera',
            'fn_validar_edad_postulante',
            'fn_verificar_capacidad_grupo',
            'fn_validar_nota',
            'fn_verificar_cupo_carrera',
            'fn_recalcular_promedio',
            'fn_correo_unico_postulante',
            'fn_limite_grupos_docente',
            'fn_estado_admision_automatico',
        ];

        foreach ($funciones as $fn) {
            DB::unprepared("DROP FUNCTION IF EXISTS {$fn}();");
        }
    }
};
INSERT INTO asignacion(
            co_asignado, co_tomo, co_asignador, fe_asignacion)
    SELECT distinct p.usu_crea_id, f.codi_tomo , 1, now()
    FROM planilla_historicas p join
    folios f on p.codi_folio = f.codi_folio


CREATE OR REPLACE VIEW lv_datos_tomo AS 
 SELECT r.tomo, r.folios, r.resumen, r.digitables, r.digitados, r.digitables - r.digitados AS por_digitar, r.registros, 
        CASE
            WHEN r.digitados = 0 THEN 'POR DIGITAR'::text
            WHEN r.digitados > 0 AND r.digitados < r.digitables THEN 'INCOMPLETO'::text
            WHEN r.digitados = r.digitables THEN 'COMPLETO'::text
            ELSE NULL::text
        END AS estado, r.digitados = r.digitables AS completo
   FROM ( SELECT t.codi_tomo AS tomo, t.folios_tomo AS folios, ( SELECT count(f.codi_folio) AS count
                   FROM folios f
                  WHERE (f.reg_folio IS NULL OR f.reg_folio = 0) AND f.codi_tomo = t.codi_tomo) AS resumen, ( SELECT count(f.codi_folio) AS count
                   FROM folios f
                  WHERE (f.reg_folio IS NOT NULL OR f.reg_folio > 0) AND f.codi_tomo = t.codi_tomo) AS digitables, count(DISTINCT p.codi_folio) AS digitados,
		( SELECT sum(f.reg_folio) AS count
                   FROM folios f WHERE f.codi_tomo = t.codi_tomo) AS registros
           FROM tomos t
      JOIN folios ff ON ff.codi_tomo = t.codi_tomo
   LEFT JOIN planilla_historicas p ON p.codi_folio = ff.codi_folio
  GROUP BY t.codi_tomo) r;

ALTER TABLE lv_datos_tomo
  OWNER TO postgres;

CREATE OR REPLACE FUNCTION fn_planilla(
IN aid integer,
IN aano_peri_tpe character varying(30), 
IN anume_peri_tpe character varying(2), 
IN avalo_calc_phi character varying(150),
IN atipo_plan_tpl character varying(5), 
IN asubt_plan_stp character varying(2),
IN acodi_empl_per character varying(100),
IN acodi_conc_tco character varying(5),
IN acodi_folio integer,
IN adesc_plan_stp text, 
IN aflag_folio integer,
IN anum_reg integer,
IN ausu_crea_id integer,
IN ausu_mod_id integer=null)
RETURNS INTEGER AS
$BODY$
BEGIN
IF EXISTS(SELECT * FROM planilla_historicas WHERE id = aid) THEN
   UPDATE planilla_historicas
   SET ano_peri_tpe=aano_peri_tpe, nume_peri_tpe=anume_peri_tpe, 
	valo_calc_phi=avalo_calc_phi, tipo_plan_tpl=atipo_plan_tpl, 
       subt_plan_stp=asubt_plan_stp, codi_empl_per=acodi_empl_per,
       codi_conc_tco=acodi_conc_tco, codi_folio=acodi_folio, 
       desc_plan_stp=adesc_plan_stp, flag_folio=aflag_folio,
	num_reg=anum_reg, usu_mod_id=ausu_mod_id, fec_mod=now()
WHERE id=aid;
RETURN aid;
ELSE
INSERT INTO planilla_historicas(
            ano_peri_tpe, nume_peri_tpe, valo_calc_phi, tipo_plan_tpl, 
            subt_plan_stp, codi_empl_per, codi_conc_tco, codi_folio, 
            desc_plan_stp, flag_folio, num_reg, usu_crea_id, fec_creac)
    VALUES (aano_peri_tpe, anume_peri_tpe, avalo_calc_phi, atipo_plan_tpl, 
            asubt_plan_stp, acodi_empl_per, acodi_conc_tco, acodi_folio, 
            adesc_plan_stp, aflag_folio, anum_reg, 
            ausu_crea_id,now());
            RETURN 1;
END IF;
END;
$BODY$
LANGUAGE plpgsql

-- Function: fn_fixplanilla(integer)

-- DROP FUNCTION fn_fixplanilla(integer);

CREATE OR REPLACE FUNCTION fn_fixplanilla(acodi_folio integer)
  RETURNS integer AS
$BODY$
DECLARE registros integer;
BEGIN
--BORRA FILAS INNECESARIAS
SELECT COALESCE(reg_folio-1,-1) FROM folios WHERE codi_folio=acodi_folio INTO registros;
DELETE FROM planilla_historicas WHERE codi_folio = acodi_folio
AND num_reg>registros;
--BORRA COLUMNAS INNECESARIAS
--DELETE FROM planilla_historicas p WHERE p.codi_folio = acodi_folio
--AND p.codi_conc_tco not in (SELECT codi_conc_tco FROM conceptos_folios cp1 where cp1.codi_folio = acodi_folio);
--id in ( SELECT p.id FROM planilla_historicas p WHERE p.codi_conc_tco in (SELECT codi_conc_tco FROM conceptos_folios cp1 where cp1.codi_folio = acodi_folio) and 
--p.flag_folio > (SELECT count(cp.codi_conc_tco) FROM conceptos_folios cp where cp.codi_folio = acodi_folio and cp.codi_conc_tco=p.codi_conc_tco)
--);
--RETURN 1;
--BORRA COLUMNAS INNECESARIAS
DELETE FROM planilla_historicas WHERE codi_folio = acodi_folio
AND codi_conc_tco not in (SELECT codi_conc_tco FROM conceptos_folios where codi_folio = acodi_folio);
RETURN 1;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fn_fixplanilla(integer)
  OWNER TO postgres;

-- Function: fn_planilla(integer, character varying, character varying, character varying, character varying, character varying, character varying, character varying, integer, text, integer, integer, integer, integer)

-- DROP FUNCTION fn_planilla(integer, character varying, character varying, character varying, character varying, character varying, character varying, character varying, integer, text, integer, integer, integer, integer);

CREATE OR REPLACE FUNCTION fn_planilla(aid integer, aano_peri_tpe character varying, anume_peri_tpe character varying, avalo_calc_phi character varying, atipo_plan_tpl character varying, asubt_plan_stp character varying, acodi_empl_per character varying, acodi_conc_tco character varying, acodi_folio integer, adesc_plan_stp text, aflag_folio integer, anum_reg integer, ausu_crea_id integer, ausu_mod_id integer DEFAULT NULL::integer)
  RETURNS integer AS
$BODY$
BEGIN
IF EXISTS(SELECT * FROM planilla_historicas WHERE id = aid) THEN
   UPDATE planilla_historicas
   SET ano_peri_tpe=aano_peri_tpe, nume_peri_tpe=anume_peri_tpe, 
	valo_calc_phi=avalo_calc_phi, tipo_plan_tpl=atipo_plan_tpl, 
       subt_plan_stp=asubt_plan_stp, codi_empl_per=acodi_empl_per,
       codi_conc_tco=acodi_conc_tco, codi_folio=acodi_folio, 
       desc_plan_stp=adesc_plan_stp, flag_folio=aflag_folio,
	num_reg=anum_reg, usu_mod_id=ausu_mod_id, fec_mod=now()
WHERE id=aid;
RETURN aid;
ELSE
INSERT INTO planilla_historicas(
            ano_peri_tpe, nume_peri_tpe, valo_calc_phi, tipo_plan_tpl, 
            subt_plan_stp, codi_empl_per, codi_conc_tco, codi_folio, 
            desc_plan_stp, flag_folio, num_reg, usu_crea_id, fec_creac)
    VALUES (aano_peri_tpe, anume_peri_tpe, avalo_calc_phi, atipo_plan_tpl, 
            asubt_plan_stp, acodi_empl_per, acodi_conc_tco, acodi_folio, 
            adesc_plan_stp, aflag_folio, anum_reg, 
            ausu_crea_id,now());
            RETURN 1;
END IF;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fn_planilla(integer, character varying, character varying, character varying, character varying, character varying, character varying, character varying, integer, text, integer, integer, integer, integer)
  OWNER TO postgres;

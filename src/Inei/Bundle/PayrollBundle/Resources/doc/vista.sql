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
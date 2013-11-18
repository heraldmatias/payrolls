CREATE OR REPLACE FUNCTION fn_position_concepto_update(concepto character varying, folio integer, pk integer)
  RETURNS integer AS
$BODY$
DECLARE pos integer;
BEGIN
select position
from (
select row_number() OVER(ORDER BY orden_conc_folio ASC) AS position, * from conceptos_folios 
where codi_folio=folio and codi_conc_tco=concepto
ORDER BY orden_conc_folio ASC) as t
where id = pk into pos;
return pos;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;


CREATE OR REPLACE FUNCTION fn_number_update_repetidos(concepto character varying, folio integer)
  RETURNS integer AS
$BODY$
DECLARE c_repetidos integer;
DECLARE c_repetidos_actual integer;
BEGIN
SELECT count(distinct flag_folio) FROM planilla_historicas WHERE codi_conc_tco=concepto AND codi_folio=folio AND num_reg=1 INTO c_repetidos;
SELECT count(codi_conc_tco) FROM conceptos_folios WHERE codi_conc_tco=concepto and codi_folio=folio INTO c_repetidos_actual;
RETURN c_repetidos_actual - c_repetidos;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

    
CREATE OR REPLACE FUNCTION fn_update_concepto_folio_planilla()
  RETURNS trigger AS  
$BODY$
DECLARE repetidos integer;
  DECLARE pos integer;
BEGIN
SELECT fn_position_concepto_update(OLD.codi_conc_tco, NEW.codi_folio, OLD.id) INTO pos;
UPDATE planilla_historicas set codi_conc_tco = NEW.codi_conc_tco, flag_folio = 1
WHERE codi_folio = NEW.codi_folio and codi_conc_tco = OLD.codi_conc_tco AND flag_folio=pos;
SELECT fn_number_update_repetidos(OLD.codi_conc_tco, NEW.codi_folio) INTO repetidos;
IF repetidos > 0 THEN
UPDATE planilla_historicas SET flag_folio = flag_folio - repetidos
WHERE codi_folio = NEW.codi_folio AND codi_conc_tco = OLD.codi_conc_tco AND flag_folio>repetidos;
END IF;
RETURN NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER update_concepto_folio_planilla
    BEFORE UPDATE ON conceptos_folios
    FOR EACH ROW
    WHEN (OLD.codi_conc_tco IS DISTINCT FROM NEW.codi_conc_tco)
    EXECUTE PROCEDURE fn_update_concepto_folio_planilla();
    

CREATE OR REPLACE FUNCTION fn_update_folios_planilla()
  RETURNS trigger AS  
$BODY$
BEGIN
IF (OLD.reg_folio > NEW.reg_folio) THEN
DELETE FROM planilla_historicas WHERE codi_folio = OLD.codi_folio
AND num_reg>(NEW.reg_folio-1);
END IF;
RETURN NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER update_folios_planilla
    AFTER UPDATE ON folios
    FOR EACH ROW
    WHEN (OLD.reg_folio > NEW.reg_folio)
    EXECUTE PROCEDURE fn_update_folios_planilla();

--DELETE FROM planilla_historicas WHERE codi_folio = acodi_folio
--AND codi_conc_tco not in (SELECT codi_conc_tco FROM conceptos_folios where codi_folio = acodi_folio);
select * from planilla_historicas where codi_folio=18489;
select * from conceptos_folios where codi_folio=18489;

select fn_number_update_repetidos('C12', 18489);
SELECT fn_position_concepto_update('C12', 18489, 269057);
select * from conceptos_folios where codi_folio=18489;

select * from folios limit 5;
UPDATE planilla_historicas SET flag_folio = 1  WHERE codi_folio = 18489 and flag_folio=0;
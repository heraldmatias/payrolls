create view lv_registro_digitador
AS
select count(distinct num_reg)
from planilla_historicas
where usu_crea_id = 1
group by codi_folio

SELECT * FROM planilla_historicas ORDER BY num_reg ASC;